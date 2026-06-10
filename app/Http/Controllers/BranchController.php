<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\SalesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::where('user_id', Auth::id())->get();
        return view('owner.kelola-cabang', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $locCode      = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $request->location), 0, 3));
        $randomNumber = str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
        $branchCode   = "SLS-{$locCode}-{$randomNumber}";

        Branch::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'location'    => $request->location,
            'branch_code' => $branchCode,
            'status'      => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Cabang baru berhasil ditambahkan! Token: ' . $branchCode);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status'   => 'required|in:aktif,nonaktif',
        ]);

        $branch = Branch::where('branch_id', $id)->where('user_id', Auth::id())->firstOrFail();

        $branch->update([
            'name'     => $request->name,
            'location' => $request->location,
            'status'   => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data cabang ' . $branch->name . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $branch     = Branch::where('branch_id', $id)->where('user_id', Auth::id())->firstOrFail();
        $branchName = $branch->name;
        $branch->delete();

        return redirect()->back()->with('success', 'Cabang ' . $branchName . ' berhasil dihapus secara permanen.');
    }

    public function daftarToko()
    {
        $branches = Branch::where('user_id', Auth::id())->get();

        $currentYear  = \Carbon\Carbon::now()->year;
        $currentMonth = \Carbon\Carbon::now()->month;
        $namaBulan    = \Carbon\Carbon::now()->translatedFormat('F');

        $branchIds       = $branches->pluck('branch_id');
        $totalOwnerSales = SalesModel::whereIn('branch_id', $branchIds)->sum('total_sales');

        $tokoData = [];
        $themes   = ['theme-blue', 'theme-orange', 'theme-green', 'theme-purple', 'theme-red'];

        foreach ($branches as $index => $branch) {
            $branchId = $branch->branch_id;
            $query    = SalesModel::where('branch_id', $branchId);

            $totalPenjualan = (clone $query)->sum('total_sales');
            $totalTransaksi = (clone $query)->count();

            $omsetBulanIni = (clone $query)
                ->whereYear('invoice_date', $currentYear)
                ->whereMonth('invoice_date', $currentMonth)
                ->sum('total_sales');

            $kontribusi = $totalOwnerSales > 0
                ? ($totalPenjualan / $totalOwnerSales) * 100
                : 0;

            $rataRata = $totalTransaksi > 0
                ? $totalPenjualan / $totalTransaksi
                : 0;

            $tokoData[] = [
                'name'            => $branch->name,
                'location'        => $branch->location ?? 'Lokasi belum diatur',
                'code'            => $branch->branch_code ?? 'SLS-00' . ($index + 1),
                'status'          => ucfirst($branch->status ?? 'aktif'),
                'total_penjualan' => $totalPenjualan,
                'total_transaksi' => $totalTransaksi,
                'omset_bulan_ini' => $omsetBulanIni,
                'kontribusi'      => round($kontribusi, 1),
                'rata_rata'       => $rataRata,
                'theme'           => $themes[$index % count($themes)],
                'initial'         => strtoupper(substr($branch->name, 0, 1)),
            ];
        }

        usort($tokoData, fn($a, $b) => $b['total_penjualan'] <=> $a['total_penjualan']);

        return view('owner.daftar-toko', compact('tokoData', 'namaBulan'));
    }
}