<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusTokoModel;
use App\Models\SalesModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;

class TrenPenjualanTokoController extends Controller
{
    // =============================================
    // MESIN INFERENSI FORWARD CHAINING (PRIVATE)
    // Dipanggil otomatis, tidak diekspos ke UI
    // =============================================
    private function inferStatus($salesAwal, $salesAkhir): array
    {
        // R1: Toko Baru
        if ($salesAwal == 0 && $salesAkhir > 0) {
            return ['growth' => null, 'status' => 'Toko Baru'];
        }

        // Tidak ada data tahun akhir → skip
        if ($salesAkhir == 0) {
            return ['growth' => null, 'status' => null];
        }

        $growth = (($salesAkhir - $salesAwal) / $salesAwal) * 100;

        // R2: Berkembang Pesat
        if ($growth >= 20)  return ['growth' => $growth, 'status' => 'Berkembang Pesat'];

        // R3: Tumbuh
        if ($growth >= 5)   return ['growth' => $growth, 'status' => 'Tumbuh'];

        // R4: Stagnan
        if ($growth >= -5)  return ['growth' => $growth, 'status' => 'Stagnan'];

        // R5: Menurun
        if ($growth >= -20) return ['growth' => $growth, 'status' => 'Menurun'];

        // R6: Kritis (default)
        return ['growth' => $growth, 'status' => 'Kritis'];
    }

    // =============================================
    // AUTO-PROSES FC — dipanggil dari trenPenjualanToko
    // Hanya proses pasangan tahun yang belum ada di DB
    // =============================================
    private function autoProsesFc(int $userId, $branchIds): void
    {
        // Ambil semua tahun yang ada di data transaksi
        $tahunList = SalesModel::whereIn('branch_id', $branchIds)
            ->selectRaw('YEAR(invoice_date) as tahun')
            ->distinct()
            ->orderBy('tahun')
            ->pluck('tahun')
            ->toArray();

        if (count($tahunList) < 2) return; // butuh minimal 2 tahun

        $branches = Branch::where('user_id', $userId)->get();

        // Proses setiap pasangan tahun berurutan: [2021→2022, 2022→2023, dst]
        for ($i = 0; $i < count($tahunList) - 1; $i++) {
            $yearAwal  = $tahunList[$i];
            $yearAkhir = $tahunList[$i + 1];

            // Cek apakah pasangan ini sudah pernah diproses
            $sudahAda = StatusTokoModel::where('user_id', $userId)
                ->where('year_awal',  $yearAwal)
                ->where('year_akhir', $yearAkhir)
                ->exists();

            if ($sudahAda) continue; // skip, tidak perlu proses ulang

            // Proses FC untuk pasangan tahun ini
            foreach ($branches as $branch) {
                $salesAwal = SalesModel::where('branch_id', $branch->branch_id)
                    ->whereYear('invoice_date', $yearAwal)
                    ->sum('total_sales');

                $salesAkhir = SalesModel::where('branch_id', $branch->branch_id)
                    ->whereYear('invoice_date', $yearAkhir)
                    ->sum('total_sales');

                $result = $this->inferStatus($salesAwal, $salesAkhir);

                if (is_null($result['status'])) continue; // skip cabang tanpa data

                StatusTokoModel::create([
                    'user_id'        => $userId,
                    'shopping_mall'  => $branch->name,
                    'year_awal'      => $yearAwal,
                    'year_akhir'     => $yearAkhir,
                    'sales_awal'     => $salesAwal,
                    'sales_akhir'    => $salesAkhir,
                    'growth_percent' => $result['growth'] ?? 0,
                    'status_toko'    => $result['status'],
                ]);
            }
        }
    }

    // =============================================
    // ROUTE HANDLER — hanya untuk backward compat
    // Bisa dihapus dari routes jika tidak dipakai
    // =============================================
    public function prosesStatusToko($yearAwal, $yearAkhir)
    {
        $userId    = Auth::user()->user_id;
        $branchIds = Branch::where('user_id', $userId)->pluck('branch_id');

        // Hapus data lama untuk periode ini lalu proses ulang
        StatusTokoModel::where('user_id', $userId)
            ->where('year_awal',  $yearAwal)
            ->where('year_akhir', $yearAkhir)
            ->delete();

        $branches = Branch::where('user_id', $userId)->get();
        $hasil    = [];

        foreach ($branches as $branch) {
            $salesAwal  = SalesModel::where('branch_id', $branch->branch_id)
                ->whereYear('invoice_date', $yearAwal)->sum('total_sales');
            $salesAkhir = SalesModel::where('branch_id', $branch->branch_id)
                ->whereYear('invoice_date', $yearAkhir)->sum('total_sales');

            $result = $this->inferStatus($salesAwal, $salesAkhir);

            if (is_null($result['status'])) continue;

            StatusTokoModel::create([
                'user_id'        => $userId,
                'shopping_mall'  => $branch->name,
                'year_awal'      => $yearAwal,
                'year_akhir'     => $yearAkhir,
                'sales_awal'     => $salesAwal,
                'sales_akhir'    => $salesAkhir,
                'growth_percent' => $result['growth'] ?? 0,
                'status_toko'    => $result['status'],
            ]);

            $hasil[] = ['branch' => $branch->name, 'status' => $result['status']];
        }

        return response()->json([
            'message'     => 'Forward Chaining berhasil',
            'periode'     => "$yearAwal - $yearAkhir",
            'jumlah_toko' => count($hasil),
            'data'        => $hasil,
        ]);
    }

    // =============================================
    // MAIN VIEW
    // =============================================
    public function trenPenjualanToko(Request $request)
    {
        $userId    = Auth::user()->user_id;
        $branchIds = Branch::where('user_id', $userId)->pluck('branch_id');

        $adaData = SalesModel::whereIn('branch_id', $branchIds)->exists();

        if (!$adaData) {
            return view('owner.tren-penjualan-toko', [
                'tahun'               => date('Y'),
                'toko'                => 'all',
                'tahunList'           => collect(),
                'tokoList'            => collect(),
                'statusCabang'        => collect(),
                'chartLabels'         => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                'chartDatasets'       => [],
                'jumlahBerkembang'    => 0,
                'jumlahTumbuh'        => 0,
                'jumlahStagnan'       => 0,
                'jumlahMenurun'       => 0,
                'jumlahKritis'        => 0,
                'pertumbuhanTertinggi'=> null,
                'penurunanTerbesar'   => null,
                'dataForwardTersedia' => false,
                'isEmpty'             => true,
            ]);
        }

        // ← FC berjalan otomatis di sini, sebelum data ditampilkan
        $this->autoProsesFc($userId, $branchIds);

        // Dropdown
        $tahunList = SalesModel::whereIn('branch_id', $branchIds)
            ->selectRaw('YEAR(invoice_date) as tahun')
            ->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $tokoList = Branch::where('user_id', $userId)
            ->orderBy('name')
            ->pluck('name', 'branch_id');

        $tahun = $request->tahun ?? $tahunList->first() ?? date('Y');
        $toko  = $request->toko  ?? 'all';

        // Resolve nama toko jika filter spesifik
        $namaTokoDipilih = null;
        if ($toko !== 'all') {
            $namaTokoDipilih = Branch::where('branch_id', $toko)
                ->where('user_id', $userId)->value('name');
        }

        // Status dari hasil FC
        $statusQuery = StatusTokoModel::where('user_id', $userId)
            ->where('year_akhir', $tahun);

        if ($namaTokoDipilih) {
            $statusQuery->where('shopping_mall', $namaTokoDipilih);
        }

        $statusCabang        = $statusQuery->orderBy('shopping_mall')->get();
        $dataForwardTersedia = $statusCabang->count() > 0;

        // Insight
        $pertumbuhanTertinggi = null;
        $penurunanTerbesar    = null;

        if ($dataForwardTersedia) {
            $base = StatusTokoModel::where('user_id', $userId)->where('year_akhir', $tahun);
            $pertumbuhanTertinggi = (clone $base)->orderByDesc('growth_percent')->first();
            $penurunanTerbesar    = (clone $base)->orderBy('growth_percent')->first();
        }

        // Grafik
        $chartLabels   = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $chartDatasets = [];

        $targetBranches = $toko === 'all'
            ? Branch::where('user_id', $userId)->orderBy('name')->get()
            : Branch::where('user_id', $userId)->where('branch_id', $toko)->get();

        foreach ($targetBranches as $branch) {
            $dataBulanan = SalesModel::where('branch_id', $branch->branch_id)
                ->whereYear('invoice_date', $tahun)
                ->selectRaw('MONTH(invoice_date) as bulan, SUM(total_sales) as total_penjualan')
                ->groupBy('bulan')->orderBy('bulan')->get();

            $sales = [];
            for ($i = 1; $i <= 12; $i++) {
                $b       = $dataBulanan->where('bulan', $i)->first();
                $sales[] = $b ? (float) $b->total_penjualan : 0;
            }

            $chartDatasets[] = ['label' => $branch->name, 'data' => $sales];
        }

        // Summary count
        $jumlahBerkembang = $statusCabang->where('status_toko', 'Berkembang Pesat')->count();
        $jumlahTumbuh     = $statusCabang->where('status_toko', 'Tumbuh')->count();
        $jumlahStagnan    = $statusCabang->where('status_toko', 'Stagnan')->count();
        $jumlahMenurun    = $statusCabang->where('status_toko', 'Menurun')->count();
        $jumlahKritis     = $statusCabang->where('status_toko', 'Kritis')->count();

        return view('owner.tren-penjualan-toko', compact(
            'tahun', 'toko', 'tahunList', 'tokoList',
            'statusCabang', 'chartLabels', 'chartDatasets',
            'jumlahBerkembang', 'jumlahTumbuh', 'jumlahStagnan',
            'jumlahMenurun', 'jumlahKritis',
            'pertumbuhanTertinggi', 'penurunanTerbesar', 'dataForwardTersedia'
        ) + ['isEmpty' => false]);
    }
}