@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-slate-900">Input Data Transaksi</h1>
    <p class="text-sm text-slate-500 mt-1">Tambahkan pencatatan data penjualan baru ke dalam sistem</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden max-w-2xl">
    
    <form action="{{ route('admin.input.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="calendar" class="w-4 h-4 {{ $errors->has('invoice_date') ? 'text-red-400' : 'text-slate-400' }}"></i>
                </div>
                <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required 
                    class="block w-full pl-11 pr-4 py-3 bg-white border rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 transition-all
                    {{ $errors->has('invoice_date') ? 'border-red-300 focus:ring-red-500 focus:border-red-500 bg-red-50/30' : 'border-slate-200 focus:ring-blue-500 focus:border-transparent' }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="tag" class="w-4 h-4 {{ $errors->has('category') ? 'text-red-400' : 'text-slate-400' }}"></i>
                </div>
                <select name="category" required 
                    class="block w-full pl-11 pr-10 py-3 bg-white border rounded-xl text-sm text-slate-700 appearance-none focus:outline-none focus:ring-2 transition-all cursor-pointer
                    {{ $errors->has('category') ? 'border-red-300 focus:ring-red-500 focus:border-red-500 bg-red-50/30' : 'border-slate-200 focus:ring-blue-500 focus:border-transparent' }}">
                    <option value="" disabled {{ old('category') == '' ? 'selected' : '' }}>Pilih kategori...</option>
                    <option value="Clothing" {{ old('category') == 'Clothing' ? 'selected' : '' }}>Clothing (Pakaian)</option>
                    <option value="Shoes" {{ old('category') == 'Shoes' ? 'selected' : '' }}>Shoes (Sepatu)</option>
                    <option value="Food & Beverage" {{ old('category') == 'Food & Beverage' ? 'selected' : '' }}>Food & Beverage</option>
                    <option value="Cosmetics" {{ old('category') == 'Cosmetics' ? 'selected' : '' }}>Cosmetics</option>
                    <option value="Books" {{ old('category') == 'Books' ? 'selected' : '' }}>Books (Buku)</option>
                    <option value="Toys" {{ old('category') == 'Toys' ? 'selected' : '' }}>Toys (Mainan)</option>
                    <option value="Technology" {{ old('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                    <option value="Souvenir" {{ old('category') == 'Souvenir' ? 'selected' : '' }}>Souvenir</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" id="input-jumlah" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Misal: 10" required 
                    class="block w-full px-4 py-3 bg-white border rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 transition-all
                    {{ $errors->has('quantity') ? 'border-red-300 focus:ring-red-500 focus:border-red-500 bg-red-50/30' : 'border-slate-200 focus:ring-blue-500 focus:border-transparent' }}">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" id="input-harga" name="price" value="{{ old('price') }}" min="0" placeholder="Misal: 5000" required 
                    class="block w-full px-4 py-3 bg-white border rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 transition-all
                    {{ $errors->has('price') ? 'border-red-300 focus:ring-red-500 focus:border-red-500 bg-red-50/30' : 'border-slate-200 focus:ring-blue-500 focus:border-transparent' }}">
            </div>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl mt-2">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                Estimasi Total Pendapatan
            </label>
            <input type="text" id="input-total" readonly placeholder="Rp0" 
                class="block w-full bg-transparent text-xl md:text-2xl font-extrabold text-slate-300 cursor-not-allowed focus:outline-none border-none p-0">
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
            <a href="{{ route('admin.transaksi') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                Kembali
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold text-sm rounded-xl hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm shadow-blue-600/30">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Data
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jumlahInput = document.getElementById('input-jumlah');
        const hargaInput = document.getElementById('input-harga');
        const totalInput = document.getElementById('input-total');

        function hitungTotal() {
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;
            const total = jumlah * harga;

            if (total > 0) {
                // Menggunakan toLocaleString untuk format uang otomatis
                totalInput.value = 'Rp' + total.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 2});
                totalInput.classList.remove('text-slate-300');
                totalInput.classList.add('text-blue-600'); 
            } else {
                totalInput.value = 'Rp0';
                totalInput.classList.add('text-slate-300');
                totalInput.classList.remove('text-blue-600');
            }
        }

        jumlahInput.addEventListener('input', hitungTotal);
        hargaInput.addEventListener('input', hitungTotal);
        hitungTotal(); // Panggil saat pertama kali diload
    });
</script>
@endsection