<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Sale; // Asumsi nama modelmu adalah Sale atau ganti menjadi Transaction jika namamu itu

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil ID cabang dari admin yang sedang login
        $branchId = $user->branch_id;

        // Base query: HANYA ambil data transaksi di cabang milik admin tersebut
        $query = Sale::where('branch_id', $branchId); // Sesuaikan dengan nama Model-mu

        // 1. Hitung Statistik (Sesuai kolom database aslimu)
        $totalTransaksi = (clone $query)->count();
        
        // Menggunakan kolom 'total_sales' langsung
        $totalPendapatan = (clone $query)->sum('total_sales'); 
        
        // Menggunakan kolom 'quantity'
        $totalQty = (clone $query)->sum('quantity'); 
        
        $rataRata = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        // 2. Data Top 5 Produk (Menggunakan kolom 'category' karena tidak ada product_name)
        $topProduk = (clone $query)
            ->select('category', DB::raw('SUM(total_sales) as total_revenue'))
            ->groupBy('category')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Cari pendapatan maksimal untuk menghitung tinggi batang Bar Chart
        $maxRevenue = $topProduk->max('total_revenue') ?? 1;
        $maxRevenue = $maxRevenue > 0 ? $maxRevenue : 1; 

        // 3. Transaksi Terbaru (5 entri terakhir berdasarkan 'invoice_date')
        $transaksiTerbaru = (clone $query)
            ->orderBy('invoice_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'totalTransaksi',
            'totalPendapatan',
            'totalQty',
            'rataRata',
            'topProduk',
            'maxRevenue',
            'transaksiTerbaru'
        ));
    }
}