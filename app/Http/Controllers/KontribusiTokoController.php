<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HasilEdasModel;
use App\Models\SalesModel;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

class KontribusiTokoController extends Controller
{
    // =============================================
    // PROSES EDAS — bisa dipanggil manual via route
    // =============================================
    public function prosesEdas($tahun)
    {
        $userId    = Auth::user()->user_id;
        $branchIds = Branch::where('user_id', $userId)->pluck('branch_id');

        HasilEdasModel::where('user_id', $userId)
            ->where('periode_year', $tahun)
            ->delete();

        $data = SalesModel::select(
                'branch_id',
                DB::raw('SUM(total_sales) as total_sales'),
                DB::raw('COUNT(invoice_no) as total_transaction'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('AVG(total_sales) as average_sales')
            )
            ->whereIn('branch_id', $branchIds)
            ->whereYear('invoice_date', $tahun)
            ->groupBy('branch_id')
            ->get();

        if ($data->count() == 0) {
            return response()->json(['message' => 'Data tidak ditemukan']);
        }

        // Map branch_id ke nama
        $branchNames = Branch::whereIn('branch_id', $branchIds)
            ->pluck('name', 'branch_id');

        $avg_sales       = $data->avg('total_sales');
        $avg_transaction = $data->avg('total_transaction');
        $avg_quantity    = $data->avg('total_quantity');
        $avg_avg_sales   = $data->avg('average_sales');

        $weights = [
            'sales'        => 0.4,
            'transaction'  => 0.3,
            'quantity'     => 0.2,
            'average_sales'=> 0.1,
        ];

        $results = [];

        foreach ($data as $item) {
            $pda_sales       = max(0, ($item->total_sales - $avg_sales) / $avg_sales);
            $pda_transaction = max(0, ($item->total_transaction - $avg_transaction) / $avg_transaction);
            $pda_quantity    = max(0, ($item->total_quantity - $avg_quantity) / $avg_quantity);
            $pda_avg_sales   = max(0, ($item->average_sales - $avg_avg_sales) / $avg_avg_sales);

            $nda_sales       = max(0, ($avg_sales - $item->total_sales) / $avg_sales);
            $nda_transaction = max(0, ($avg_transaction - $item->total_transaction) / $avg_transaction);
            $nda_quantity    = max(0, ($avg_quantity - $item->total_quantity) / $avg_quantity);
            $nda_avg_sales   = max(0, ($avg_avg_sales - $item->average_sales) / $avg_avg_sales);

            $sp = ($pda_sales * $weights['sales']) + ($pda_transaction * $weights['transaction'])
                + ($pda_quantity * $weights['quantity']) + ($pda_avg_sales * $weights['average_sales']);

            $sn = ($nda_sales * $weights['sales']) + ($nda_transaction * $weights['transaction'])
                + ($nda_quantity * $weights['quantity']) + ($nda_avg_sales * $weights['average_sales']);

            $results[] = [
                'branch_id'          => $item->branch_id,
                'shopping_mall'      => $branchNames[$item->branch_id] ?? 'Unknown',
                'periode_year'       => $tahun,
                'total_sales'        => $item->total_sales,
                'total_transaction'  => $item->total_transaction,
                'total_quantity'     => $item->total_quantity,
                'average_sales'      => $item->average_sales,
                'pda_sales'          => $pda_sales,
                'pda_transaction'    => $pda_transaction,
                'pda_quantity'       => $pda_quantity,
                'pda_average_sales'  => $pda_avg_sales,
                'nda_sales'          => $nda_sales,
                'nda_transaction'    => $nda_transaction,
                'nda_quantity'       => $nda_quantity,
                'nda_average_sales'  => $nda_avg_sales,
                'sp'                 => $sp,
                'sn'                 => $sn,
            ];
        }

        $maxSp = collect($results)->max('sp');
        $maxSn = collect($results)->max('sn');

        foreach ($results as &$result) {
            $nsp = $maxSp > 0 ? $result['sp'] / $maxSp : 0;
            $nsn = $maxSn > 0 ? 1 - ($result['sn'] / $maxSn) : 1;
            $result['nsp']            = $nsp;
            $result['nsn']            = $nsn;
            $result['appraisal_score']= ($nsp + $nsn) / 2;
        }

        usort($results, fn($a, $b) => $b['appraisal_score'] <=> $a['appraisal_score']);

        foreach ($results as $index => $result) {
            HasilEdasModel::create([
                'user_id'            => $userId,
                'shopping_mall'      => $result['shopping_mall'],
                'periode_year'       => $result['periode_year'],
                'total_sales'        => $result['total_sales'],
                'total_transaction'  => $result['total_transaction'],
                'total_quantity'     => $result['total_quantity'],
                'average_sales'      => $result['average_sales'],
                'pda_sales'          => $result['pda_sales'],
                'pda_transaction'    => $result['pda_transaction'],
                'pda_quantity'       => $result['pda_quantity'],
                'pda_average_sales'  => $result['pda_average_sales'],
                'nda_sales'          => $result['nda_sales'],
                'nda_transaction'    => $result['nda_transaction'],
                'nda_quantity'       => $result['nda_quantity'],
                'nda_average_sales'  => $result['nda_average_sales'],
                'sp'                 => $result['sp'],
                'sn'                 => $result['sn'],
                'nsp'                => $result['nsp'],
                'nsn'                => $result['nsn'],
                'appraisal_score'    => $result['appraisal_score'],
                'ranking_position'   => $index + 1,
            ]);
        }

        return response()->json([
            'message' => 'Perhitungan EDAS berhasil',
            'data'    => $results,
        ]);
    }

    // =============================================
    // AUTO EDAS — dipanggil otomatis dari view
    // =============================================
    private function autoEdasUntukSemua(int $userId): void
    {
        $branchIds = Branch::where('user_id', $userId)->pluck('branch_id');

        $tahunList = SalesModel::whereIn('branch_id', $branchIds)
            ->selectRaw('YEAR(invoice_date) as tahun')
            ->distinct()->pluck('tahun')->toArray();

        foreach ($tahunList as $tahun) {
            $sudahAda = HasilEdasModel::where('user_id', $userId)
                ->where('periode_year', $tahun)->exists();

            if (!$sudahAda) {
                $this->prosesEdasInternal($userId, $branchIds, $tahun);
            }
        }
    }

    private function prosesEdasInternal(int $userId, $branchIds, $tahun): void
    {
        $data = SalesModel::select(
                'branch_id',
                DB::raw('SUM(total_sales) as total_sales'),
                DB::raw('COUNT(invoice_no) as total_transaction'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('AVG(total_sales) as average_sales')
            )
            ->whereIn('branch_id', $branchIds)
            ->whereYear('invoice_date', $tahun)
            ->groupBy('branch_id')
            ->get();

        if ($data->count() == 0) return;

        $branchNames = Branch::whereIn('branch_id', $branchIds)
            ->pluck('name', 'branch_id');

        $avg_sales       = $data->avg('total_sales');
        $avg_transaction = $data->avg('total_transaction');
        $avg_quantity    = $data->avg('total_quantity');
        $avg_avg_sales   = $data->avg('average_sales');

        $weights = ['sales' => 0.4, 'transaction' => 0.3, 'quantity' => 0.2, 'average_sales' => 0.1];

        $results = [];

        foreach ($data as $item) {
            $pda_sales       = max(0, ($item->total_sales - $avg_sales) / $avg_sales);
            $pda_transaction = max(0, ($item->total_transaction - $avg_transaction) / $avg_transaction);
            $pda_quantity    = max(0, ($item->total_quantity - $avg_quantity) / $avg_quantity);
            $pda_avg_sales   = max(0, ($item->average_sales - $avg_avg_sales) / $avg_avg_sales);

            $nda_sales       = max(0, ($avg_sales - $item->total_sales) / $avg_sales);
            $nda_transaction = max(0, ($avg_transaction - $item->total_transaction) / $avg_transaction);
            $nda_quantity    = max(0, ($avg_quantity - $item->total_quantity) / $avg_quantity);
            $nda_avg_sales   = max(0, ($avg_avg_sales - $item->average_sales) / $avg_avg_sales);

            $sp = ($pda_sales * $weights['sales']) + ($pda_transaction * $weights['transaction'])
                + ($pda_quantity * $weights['quantity']) + ($pda_avg_sales * $weights['average_sales']);

            $sn = ($nda_sales * $weights['sales']) + ($nda_transaction * $weights['transaction'])
                + ($nda_quantity * $weights['quantity']) + ($nda_avg_sales * $weights['average_sales']);

            $results[] = [
                'shopping_mall'     => $branchNames[$item->branch_id] ?? 'Unknown',
                'total_sales'       => $item->total_sales,
                'total_transaction' => $item->total_transaction,
                'total_quantity'    => $item->total_quantity,
                'average_sales'     => $item->average_sales,
                'pda_sales'         => $pda_sales, 'pda_transaction' => $pda_transaction,
                'pda_quantity'      => $pda_quantity, 'pda_average_sales' => $pda_avg_sales,
                'nda_sales'         => $nda_sales, 'nda_transaction' => $nda_transaction,
                'nda_quantity'      => $nda_quantity, 'nda_average_sales' => $nda_avg_sales,
                'sp' => $sp, 'sn' => $sn,
            ];
        }

        $maxSp = collect($results)->max('sp');
        $maxSn = collect($results)->max('sn');

        foreach ($results as &$result) {
            $nsp = $maxSp > 0 ? $result['sp'] / $maxSp : 0;
            $nsn = $maxSn > 0 ? 1 - ($result['sn'] / $maxSn) : 1;
            $result['nsp'] = $nsp;
            $result['nsn'] = $nsn;
            $result['appraisal_score'] = ($nsp + $nsn) / 2;
        }

        usort($results, fn($a, $b) => $b['appraisal_score'] <=> $a['appraisal_score']);

        HasilEdasModel::where('user_id', $userId)->where('periode_year', $tahun)->delete();

        foreach ($results as $index => $result) {
            HasilEdasModel::create(array_merge($result, [
                'user_id'          => $userId,
                'periode_year'     => $tahun,
                'ranking_position' => $index + 1,
            ]));
        }
    }

    // =============================================
    // MAIN VIEW
    // =============================================
    public function kontribusiToko(Request $request)
    {
        $userId    = Auth::user()->user_id;
        $branchIds = Branch::where('user_id', $userId)->pluck('branch_id');

        $adaData = SalesModel::whereIn('branch_id', $branchIds)->exists();

        if (!$adaData) {
            return view('owner.kontribusi-toko', [
                'isEmpty'       => true,
                'tahun'         => date('Y'),
                'tahunList'     => collect(),
                'data'          => collect(),
                'best'          => null,
                'worst'         => null,
                'totalSales'    => 0,
                'jumlahCabang'  => 0,
                'rataRataCabang'=> 0,
                'chartLabels'   => [],
                'chartScores'   => [],
                'chartColors'   => [],
                'chartSales'    => [],
            ]);
        }

        // EDAS otomatis di background
        $this->autoEdasUntukSemua($userId);

        $tahunList = HasilEdasModel::where('user_id', $userId)
            ->select('periode_year')->distinct()
            ->orderBy('periode_year', 'desc')->pluck('periode_year');

        $tahun = $request->tahun ?? $tahunList->first() ?? date('Y');

        $data = HasilEdasModel::where('user_id', $userId)
            ->where('periode_year', $tahun)
            ->orderBy('ranking_position')->get();

        $totalSales = $data->sum('total_sales');

        foreach ($data as $item) {
            $item->persentase = $totalSales > 0
                ? ($item->total_sales / $totalSales) * 100 : 0;
        }

        $best  = $data->first();
        $worst = $data->last();

        $jumlahCabang   = $data->count();
        $rataRataCabang = $jumlahCabang > 0 ? $totalSales / $jumlahCabang : 0;

        $palette = [
            '#314cff','#10b981','#f59e0b','#ef4444',
            '#8b5cf6','#06b6d4','#ec4899','#84cc16',
        ];

        $chartLabels = $data->pluck('shopping_mall')->toArray();
        $chartScores = $data->pluck('appraisal_score')->map(fn($s) => round($s, 4))->toArray();
        $chartSales  = $data->pluck('total_sales')->map(fn($s) => round($s, 0))->toArray();
        $chartColors = collect($chartLabels)->keys()
            ->map(fn($i) => $palette[$i % count($palette)])->toArray();

        return view('owner.kontribusi-toko', compact(
            'data', 'best', 'worst', 'totalSales',
            'jumlahCabang', 'rataRataCabang',
            'tahun', 'tahunList',
            'chartLabels', 'chartScores', 'chartSales', 'chartColors'
        ) + ['isEmpty' => false]);
    }
}