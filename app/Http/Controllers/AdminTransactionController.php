<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        
        // Menangkap request pencarian dan filter
        $search = $request->input('search');
        $filterCategory = $request->input('category');

        $query = Sale::where('branch_id', $branchId);

        // Logika Pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('category', 'like', "%{$search}%")
                  ->orWhere('invoice_no', 'like', "%{$search}%");
            });
        }

        // Logika Filter Kategori
        if ($filterCategory) {
            $query->where('category', $filterCategory);
        }

        $transactions = $query->orderBy('invoice_date', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);

        return view('admin.data-transaksi', compact('transactions', 'search', 'filterCategory'));
    }

    public function create()
    {
        return view('admin.input-data');
    }

    // 👇 INI DIA FUNGSI YANG HILANG (Untuk menyimpan data) 👇
    public function store(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'invoice_date' => 'required|date',
            'category'     => 'required|string',
            'quantity'     => 'required|integer|min:1',
            'price'        => 'required|numeric|min:0',
        ]);

        // 2. Generate Data Otomatis
        $invoiceNo  = 'I' . rand(100000, 999999); 
        $customerId = 'C' . rand(100000, 999999); 
        $totalSales = $request->quantity * $request->price;
        
        // Membuat pilihan acak untuk Gender agar aman masuk database
        $genders = ['Male', 'Female'];
        $randomGender = $genders[array_rand($genders)]; 
        
        // Membuat pilihan acak untuk Umur (18 - 65)
        $randomAge = rand(18, 65);

        // Ambil data admin yang sedang login
        $user = Auth::user();

        // 3. Simpan ke database
        Sale::create([
            'branch_id'      => $user->branch_id,
            'invoice_no'     => $invoiceNo,
            'customer_id'    => $customerId,
            'gender'         => $randomGender,
            'age'            => $randomAge,       
            'category'       => $request->category,
            'quantity'       => $request->quantity,
            'price'          => $request->price,
            'payment_method' => 'Cash',
            'invoice_date'   => $request->invoice_date,
            'shopping_mall'  => '-',              
            'total_sales'    => $totalSales,
        ]);

        // 4. Redirect kembali
        return redirect()->route('admin.transaksi')->with('success', 'Data transaksi berhasil ditambahkan!');
    }
    // 👆 BATAS FUNGSI STORE 👆
    
    // Menampilkan halaman Edit
    public function edit($id)
    {
        $branchId = Auth::user()->branch_id;
        // Pastikan hanya bisa mengedit transaksi milik cabangnya sendiri
        $transaction = Sale::where('branch_id', $branchId)->findOrFail($id);
        
        return view('admin.edit-transaksi', compact('transaction'));
    }

    // Memproses Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'category'     => 'required|string',
            'quantity'     => 'required|integer|min:1',
            'price'        => 'required|numeric|min:0',
        ]);

        $branchId = Auth::user()->branch_id;
        $transaction = Sale::where('branch_id', $branchId)->findOrFail($id);

        $totalSales = $request->quantity * $request->price;

        $transaction->update([
            'invoice_date' => $request->invoice_date,
            'category'     => $request->category,
            'quantity'     => $request->quantity,
            'price'        => $request->price,
            'total_sales'  => $totalSales,
        ]);

        return redirect()->route('admin.transaksi')->with('success', 'Data transaksi berhasil diperbarui!');
    }

    // Memproses Hapus Data
    public function destroy($id)
    {
        $branchId = Auth::user()->branch_id;
        $transaction = Sale::where('branch_id', $branchId)->findOrFail($id);
        
        $transaction->delete();

        return redirect()->route('admin.transaksi')->with('success', 'Data transaksi berhasil dihapus!');
    } 

    public function importCsv(Request $request)
    {
        // 1. Validasi File
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:10240', // Maksimal ukuran 10MB
        ]);

        $file = $request->file('csv_file');
        
        // 2. Buka file untuk dibaca
        $handle = fopen($file->getPathname(), 'r');
        
        // 3. Ambil baris pertama sebagai Header (Nama-nama kolom)
        $header = fgetcsv($handle, 1000, ',');
        
        // Pastikan file CSV tidak kosong
        if (!$header) {
            return redirect()->back()->withErrors('File CSV kosong atau format tidak sesuai.');
        }

        $user = Auth::user();
        $branchId = $user->branch_id;
        $records = [];

        // 4. Looping untuk membaca baris data satu per satu
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Gabungkan header dengan data agar menjadi array asosiatif (misal: ['price' => 5000])
            // Jika jumlah kolom data tidak sama dengan header, lewati baris ini agar tidak error
            if (count($header) !== count($data)) {
                continue;
            }
            $row = array_combine($header, $data);

            // Ambil qty dan price, kalau tidak ada/kosong, jadikan 0
            $qty = isset($row['quantity']) ? (int)$row['quantity'] : 1;
            $price = isset($row['price']) ? (float)$row['price'] : 0;
            
            // Hitung total sales, atau gunakan dari CSV jika ada
            $totalSales = isset($row['total_sales']) ? (float)$row['total_sales'] : ($qty * $price);

            // Parsing Tanggal (Mencegah format error)
            $invoiceDate = date('Y-m-d');
            if (!empty($row['invoice_date'])) {
                $invoiceDate = date('Y-m-d', strtotime(str_replace('/', '-', $row['invoice_date'])));
            }

            // Susun data yang akan dimasukkan ke database
            $records[] = [
                'branch_id'      => $branchId, // Otomatis memakai cabang admin yang login
                'invoice_no'     => $row['invoice_no'] ?? ('I' . rand(100000, 999999)),
                'customer_id'    => $row['customer_id'] ?? ('C' . rand(100000, 999999)),
                'gender'         => $row['gender'] ?? 'Female',
                'age'            => isset($row['age']) ? (int)$row['age'] : 25,
                'category'       => $row['category'] ?? 'Lainnya',
                'quantity'       => $qty,
                'price'          => $price,
                'payment_method' => $row['payment_method'] ?? 'Cash',
                'invoice_date'   => $invoiceDate,
                'shopping_mall'  => $row['shopping_mall'] ?? '-',
                'total_sales'    => $totalSales,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        fclose($handle);

        // 5. Masukkan ke database (Menggunakan array_chunk agar tidak berat jika datanya puluhan ribu)
        foreach (array_chunk($records, 500) as $chunk) {
            Sale::insert($chunk);
        }

        return redirect()->back()->with('success', count($records) . ' Data transaksi berhasil di-import dari CSV!');
    }
}