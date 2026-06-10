@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-slate-900">Manajemen Data Transaksi</h1>
    <p class="text-sm text-slate-500 mt-1">Kelola dan pantau data penjualan dengan mudah</p>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
    <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
    <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ $errors->first() }}
</div>
@endif

<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    
    <div class="flex items-center gap-3 w-full md:w-auto">
        <a href="{{ route('admin.input') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm transition-colors w-full md:w-auto justify-center">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Data
        </a>
        
        <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm transition-colors w-full md:w-auto justify-center">
            <i data-lucide="upload" class="w-4 h-4"></i> Import CSV
        </button>
    </div>

    <div class="flex items-center gap-3 w-full md:w-auto">
        <form action="{{ route('admin.transaksi') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3 w-full">
            
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" class="block w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Cari invoice/kategori...">
            </div>
            
            <select name="category" onchange="this.form.submit()" class="bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                <option value="">Semua Kategori</option>
                <option value="Clothing" {{ ($filterCategory ?? '') == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                <option value="Shoes" {{ ($filterCategory ?? '') == 'Shoes' ? 'selected' : '' }}>Shoes</option>
                <option value="Food & Beverage" {{ ($filterCategory ?? '') == 'Food & Beverage' ? 'selected' : '' }}>Food & Beverage</option>
                <option value="Cosmetics" {{ ($filterCategory ?? '') == 'Cosmetics' ? 'selected' : '' }}>Cosmetics</option>
                <option value="Books" {{ ($filterCategory ?? '') == 'Books' ? 'selected' : '' }}>Books</option>
                <option value="Toys" {{ ($filterCategory ?? '') == 'Toys' ? 'selected' : '' }}>Toys</option>
                <option value="Technology" {{ ($filterCategory ?? '') == 'Technology' ? 'selected' : '' }}>Technology</option>
                <option value="Souvenir" {{ ($filterCategory ?? '') == 'Souvenir' ? 'selected' : '' }}>Souvenir</option>
            </select>

            @if(!empty($search) || !empty($filterCategory))
                <a href="{{ route('admin.transaksi') }}" class="text-xs font-bold text-red-500 hover:text-red-700 underline">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-50/50 border-b border-slate-100 text-[11px] font-bold text-blue-600 tracking-wider uppercase">
                    <th class="px-6 py-4">NO</th>
                    <th class="px-6 py-4">TANGGAL</th>
                    <th class="px-6 py-4">NAMA PRODUK</th>
                    <th class="px-6 py-4">JUMLAH</th>
                    <th class="px-6 py-4">HARGA SATUAN (RP)</th>
                    <th class="px-6 py-4">TOTAL HARGA (RP)</th>
                    <th class="px-6 py-4 text-center">AKSI</th>
                </tr>
            </thead>
            
            <tbody class="text-sm text-slate-600">
                
                @forelse($transactions as $index => $item)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-400">
                        {{ $transactions->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <span class="font-semibold text-slate-700">{{ date('d M Y', strtotime($item->invoice_date)) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                <i data-lucide="package" class="w-4 h-4"></i>
                            </div>
                            <span class="font-bold text-slate-800 leading-tight">{{ Str::title(str_replace('_', ' ', $item->category)) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $item->quantity }}<span class="text-xs font-medium text-slate-400 ml-1">pcs</span></td>
                    <td class="px-6 py-4 text-slate-500">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-extrabold text-slate-900">Rp{{ number_format($item->total_sales, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.transaksi.edit', $item->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 rounded-lg text-xs font-bold transition-colors">
                                <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> Edit
                            </a>
                            
                            <form action="{{ route('admin.transaksi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 rounded-lg text-xs font-bold transition-colors">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500 font-medium">
                        Belum ada data transaksi yang ditemukan.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
    
    @if($transactions->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $transactions->appends(['search' => $search, 'category' => $filterCategory])->links('pagination::tailwind') }}
    </div>
    @else
    <div class="p-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
        <span class="text-xs font-medium text-slate-500">
            Menampilkan {{ $transactions->count() }} data transaksi.
        </span>
    </div>
    @endif
</div>

<div id="importModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 transition-opacity" aria-hidden="true" onclick="document.getElementById('importModal').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 text-blue-600 sm:mx-0 sm:h-10 sm:w-10">
                        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-extrabold text-slate-900" id="modal-title">Import Data CSV</h3>
                        <div class="mt-2">
                            <p class="text-sm text-slate-500">Pilih file CSV yang berisi data transaksi. Pastikan format kolom (header) pada baris pertama sesuai dengan standar database.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.transaksi.import') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                    @csrf
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 transition-colors">
                        <input type="file" name="csv_file" accept=".csv" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="bg-white text-slate-700 px-4 py-2 border border-slate-200 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                            <i data-lucide="upload-cloud" class="w-4 h-4"></i> Proses Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection