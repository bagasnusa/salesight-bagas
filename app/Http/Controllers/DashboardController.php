<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. ambil id cuman pny owner
        $ownerBranchIds = Branch::where('user_id', Auth::id())->pluck('branch_id');

        // 2. query dasarnya
        $query = Sale::whereIn('branch_id', $ownerBranchIds);

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // --- STATISTIK KESELURUHAN ---
        $totalCabang = $ownerBranchIds->count();
        $totalTransaksi = (clone $query)->count();
        $totalSemuaPendapatan = (clone $query)->sum('total_sales');

        // --- STATISTIK TAHUN INI ---
        $transaksiTahunIni = (clone $query)->whereYear('invoice_date', $currentYear);
        $totalPenjualanTahun = $transaksiTahunIni->sum('total_sales');

        // --- STATISTIK BULAN INI ---
        $omsetBulanIni = (clone $query)->whereYear('invoice_date', $currentYear)
                                       ->whereMonth('invoice_date', $currentMonth)
                                       ->sum('total_sales');

        // --- RATA-RATA ---
        $rataRataPerTransaksi = $totalTransaksi > 0 ? $totalSemuaPendapatan / $totalTransaksi : 0;
        $rataRataHarian = Carbon::now()->day > 0 ? $omsetBulanIni / Carbon::now()->day : 0;

        // --- DATA UNTUK GRAFIK (12 BULAN TAHUN INI) ---
        // Mengambil total sales per bulan
        $salesData = (clone $query)
            ->select(DB::raw('MONTH(invoice_date) as month'), DB::raw('SUM(total_sales) as total'))
            ->whereYear('invoice_date', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Menyusun array data untuk Chart.js/ApexCharts (Jan - Des)
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $salesData[$i] ?? 0; // Jika bulan kosong, isi 0
        }

        return view('owner.dashboard', compact(
            'totalCabang',
            'totalTransaksi',
            'totalPenjualanTahun',
            'totalSemuaPendapatan',
            'omsetBulanIni',
            'rataRataHarian',
            'rataRataPerTransaksi',
            'chartData' // Variabel baru untuk grafik
        ));
    }
}