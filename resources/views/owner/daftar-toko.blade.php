@extends('layouts.owner')

@section('content')

<div class="dt-wrapper">
    
    <div class="dt-header">
        <div class="dt-title-group">
            <h1 class="dt-title">Daftar Toko</h1>
            <p class="dt-subtitle">Profil dan performa semua cabang bisnis Salesight</p>
        </div>
        <a href="{{ route('owner.kelola-cabang') }}" class="dt-btn-primary">
            <i data-lucide="git-branch"></i> Kelola Cabang
        </a>
    </div>

    <div class="dt-grid">
        
        @forelse($tokoData as $index => $toko)
        <div class="dt-card {{ $toko['theme'] }}">
            <div class="dt-card-top">
                <div class="dt-avatar">{{ $toko['initial'] }}</div>
                <div class="dt-info">
                    <h3>{{ $toko['name'] }}</h3>
                    <p><i data-lucide="map-pin"></i> {{ $toko['location'] }}</p>
                </div>
            </div>
            
            <div class="dt-badges">
                @if($toko['status'] == 'Aktif')
                    <span class="dt-badge-status"><span class="dot"></span> Aktif</span>
                @else
                    <span class="dt-badge-status status-nonaktif"><span class="dot"></span> Nonaktif</span>
                @endif

                @if($index === 0 && $toko['total_penjualan'] > 0)
                    <span class="dt-badge-top"><i data-lucide="trophy"></i> Top Store</span>
                @endif
            </div>

            <div class="dt-stats">
                <div class="dt-stat-item">
                    <p>TOTAL PENJUALAN</p>
                    <h4>
                        @if($toko['total_penjualan'] >= 1000000)
                            Rp{{ number_format($toko['total_penjualan'] / 1000000, 0, ',', '.') }}jt
                        @else
                            Rp{{ number_format($toko['total_penjualan'], 0, ',', '.') }}
                        @endif
                    </h4>
                </div>
                <div class="dt-stat-item">
                    <p>TRANSAKSI</p>
                    <h4>{{ number_format($toko['total_transaksi'], 0, ',', '.') }}</h4>
                </div>
            </div>

            <div class="dt-highlight">
                <p>Penjualan Bulan Ini ({{ $namaBulan }})</p>
                <h2>Rp{{ number_format($toko['omset_bulan_ini'], 0, ',', '.') }}</h2>
            </div>

            <div class="dt-progress-container">
                <div class="dt-progress-text">
                    <span>Kontribusi</span>
                    <span>{{ $toko['kontribusi'] }}%</span>
                </div>
                <div class="dt-progress-bar">
                    <div class="dt-progress-fill" style="width: {{ $toko['kontribusi'] }}%;"></div>
                </div>
            </div>

            <div class="dt-card-footer">
                <span class="dt-code">{{ $toko['code'] }}</span>
                <span class="dt-avg">
                    <i data-lucide="shopping-cart"></i> 
                    @if($toko['rata_rata'] >= 1000)
                        {{ number_format($toko['rata_rata'] / 1000, 0, ',', '.') }}rb
                    @else
                        {{ number_format($toko['rata_rata'], 0, ',', '.') }}
                    @endif
                    / txn
                </span>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px; background: #ffffff; border-radius: 24px; border: 1px dashed #cbd5e1;">
            <i data-lucide="store" style="width: 48px; height: 48px; color: #94a3b8; margin-bottom: 16px;"></i>
            <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Belum Ada Cabang</h3>
            <p style="color: #64748b; font-size: 14px;">Silakan tambahkan cabang bisnis Anda melalui menu Kelola Cabang.</p>
        </div>
        @endforelse

    </div>

</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

@endsection