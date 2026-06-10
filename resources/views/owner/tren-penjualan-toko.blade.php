@extends('layouts.owner')

@section('content')

<div class="tren-toko-content">

    {{-- HEADER --}}
    <div class="tren-toko-page-header">
        <div class="tren-toko-title">Tren Penjualan Toko</div>
        <p class="tren-toko-subtitle">Analisis tren penjualan per cabang</p>
    </div>

    {{-- EMPTY STATE: owner baru, belum ada data sama sekali --}}
    @if($isEmpty)
    <div class="tren-toko-empty-state">
        <div class="tren-toko-empty-icon">
            <i data-lucide="store" style="width:40px;height:40px;color:#94a3b8;"></i>
        </div>
        <div class="tren-toko-empty-title">Belum ada data penjualan</div>
        <div class="tren-toko-empty-desc">
            Data akan muncul setelah admin menginputkan transaksi penjualan cabang.
        </div>
    </div>

    @else

    {{-- CHART CARD --}}
    <div class="tren-toko-card">
        <div class="tren-toko-card-header">
            <div>
                <div class="tren-toko-card-title">Tren Penjualan per Toko</div>
                <div class="tren-toko-card-subtitle">Perbandingan penjualan antar cabang</div>
            </div>

            <form method="GET" action="{{ route('owner.tren-toko') }}" class="tren-toko-filter-form">
                <select name="tahun" onchange="this.form.submit()" class="tren-toko-select">
                    @foreach($tahunList as $itemTahun)
                        <option value="{{ $itemTahun }}" {{ $tahun == $itemTahun ? 'selected' : '' }}>
                            {{ $itemTahun }}
                        </option>
                    @endforeach
                </select>

                <select name="toko" onchange="this.form.submit()" class="tren-toko-select">
                    <option value="all">Semua Toko</option>
                    @foreach($tokoList as $branchId => $namaToko)
                        <option value="{{ $branchId }}" {{ $toko == $branchId ? 'selected' : '' }}>
                            {{ $namaToko }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="tren-toko-chart-wrapper">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- BOTTOM CARDS --}}
    <div class="tren-toko-bottom-cards">

        {{-- STATUS TOKO --}}
        <div class="tren-toko-info-card">
            <div class="tren-toko-card-title">Status Toko</div>

            @if($dataForwardTersedia)
            <div class="tren-toko-summary-badges">
                <span class="tren-badge pesat">
                    <i data-lucide="trending-up" style="width:12px;height:12px;"></i>
                    Berkembang: {{ $jumlahBerkembang }}
                </span>
                <span class="tren-badge tumbuh">
                    <i data-lucide="arrow-up" style="width:12px;height:12px;"></i>
                    Tumbuh: {{ $jumlahTumbuh }}
                </span>
                <span class="tren-badge stagnan">
                    <i data-lucide="minus" style="width:12px;height:12px;"></i>
                    Stagnan: {{ $jumlahStagnan }}
                </span>
                <span class="tren-badge menurun">
                    <i data-lucide="trending-down" style="width:12px;height:12px;"></i>
                    Menurun: {{ $jumlahMenurun }}
                </span>
                <span class="tren-badge kritis">
                    <i data-lucide="alert-triangle" style="width:12px;height:12px;"></i>
                    Kritis: {{ $jumlahKritis }}
                </span>
            </div>
            @endif

            @if(!$dataForwardTersedia)
            <div class="tren-toko-empty-forward">
                <i data-lucide="info" style="width:16px;height:16px;flex-shrink:0;"></i>
                <div>
                    Belum ada data pembanding untuk tahun <strong>{{ $tahun }}</strong>.
                    Status toko akan otomatis muncul ketika data tahun sebelumnya tersedia.
                </div>
            </div>
            @else
            <div class="tren-toko-status-scroll">
                @foreach($statusCabang as $item)
                <div class="tren-toko-status-item">
                    <span class="tren-toko-status-dot
                        @if($item->status_toko == 'Berkembang Pesat') green
                        @elseif($item->status_toko == 'Tumbuh')        teal
                        @elseif($item->status_toko == 'Stagnan')       orange
                        @elseif($item->status_toko == 'Menurun')       red
                        @elseif($item->status_toko == 'Kritis')        darkred
                        @else gray
                        @endif">
                    </span>
                    <span class="tren-toko-status-name">{{ $item->shopping_mall }}</span>
                    <div class="tren-toko-status-right">
                        <span class="tren-toko-status-growth">
                            {{ $item->status_toko == 'Toko Baru'
                                ? '-'
                                : number_format($item->growth_percent, 1) . '%' }}
                        </span>
                        <span class="tren-toko-status-badge
                            @if($item->status_toko == 'Berkembang Pesat') badge-pesat
                            @elseif($item->status_toko == 'Tumbuh')        badge-tumbuh
                            @elseif($item->status_toko == 'Stagnan')       badge-stagnan
                            @elseif($item->status_toko == 'Menurun')       badge-menurun
                            @elseif($item->status_toko == 'Kritis')        badge-kritis
                            @else badge-baru
                            @endif">
                            {{ $item->status_toko }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- PERTUMBUHAN TOKO --}}
        <div class="tren-toko-info-card">
            <div class="tren-toko-card-title">Pertumbuhan Toko</div>

            @if(!$dataForwardTersedia)
            <div class="tren-toko-empty-forward">
                <i data-lucide="info" style="width:16px;height:16px;flex-shrink:0;"></i>
                <div>
                    Belum ada data pembanding untuk tahun <strong>{{ $tahun }}</strong>.
                    Insight akan otomatis muncul ketika data tahun sebelumnya tersedia.
                </div>
            </div>

            @else

            {{-- Pertumbuhan Tertinggi --}}
            <div class="tren-toko-insight-box blue-box">
                <div class="tren-toko-insight-icon-wrapper blue-icon">
                    <i data-lucide="trending-up" style="width:16px;height:16px;color:white;"></i>
                </div>
                <div class="tren-toko-insight-content">
                    <div class="tren-toko-insight-title blue-text">Pertumbuhan Tertinggi</div>
                    <div class="tren-toko-insight-mall">
                        {{ $pertumbuhanTertinggi->shopping_mall }}
                    </div>
                    <div class="tren-toko-insight-growth positive">
                        +{{ number_format($pertumbuhanTertinggi->growth_percent, 2) }}%
                    </div>
                    <div class="tren-toko-insight-status">
                        <span class="badge-pesat"
                            style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                            {{ $pertumbuhanTertinggi->status_toko }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Penurunan Terbesar --}}
            <div class="tren-toko-insight-box yellow-box">
                <div class="tren-toko-insight-icon-wrapper yellow-icon">
                    <i data-lucide="trending-down" style="width:16px;height:16px;color:white;"></i>
                </div>
                <div class="tren-toko-insight-content">
                    <div class="tren-toko-insight-title yellow-text">Penurunan Terbesar</div>
                    <div class="tren-toko-insight-mall">
                        {{ $penurunanTerbesar->shopping_mall }}
                    </div>
                    <div class="tren-toko-insight-growth negative">
                        {{ number_format($penurunanTerbesar->growth_percent, 2) }}%
                    </div>
                    <div class="tren-toko-insight-status">
                        <span class="
                            @if($penurunanTerbesar->status_toko == 'Kritis')  badge-kritis
                            @elseif($penurunanTerbesar->status_toko == 'Menurun') badge-menurun
                            @else badge-stagnan
                            @endif"
                            style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                            {{ $penurunanTerbesar->status_toko }}
                        </span>
                    </div>
                </div>
            </div>

            @endif
        </div>

    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    @if(!$isEmpty)
    const colors = [
        '#314cff','#10b981','#f59e0b','#ef4444','#8b5cf6',
        '#06b6d4','#ec4899','#84cc16','#f97316','#6366f1'
    ];

    const datasets = @json($chartDatasets);

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: datasets.map((item, i) => ({
                label: item.label,
                data: item.data,
                borderColor: colors[i % colors.length],
                backgroundColor: colors[i % colors.length] + '18',
                borderWidth: 2.5,
                tension: 0.35,
                fill: false,
                pointRadius: 3,
                pointHoverRadius: 5,
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    border: { display: false },
                    ticks: {
                        callback: v => v >= 1000000 ? 'Rp'+(v/1000000)+'Jt'
                                     : v >= 1000    ? 'Rp'+(v/1000)+'Rb' : v
                    }
                },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });
    @endif
});
</script>

@endsection