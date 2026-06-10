@extends('layouts.owner')

@section('content')

<div class="kontribusi-content">

    {{-- HEADER --}}
    <div class="kontribusi-page-header">
        <div class="kontribusi-title">Kontribusi Penjualan Toko</div>
        <p class="kontribusi-subtitle">Persentase kontribusi penjualan per cabang</p>
    </div>

    @if($isEmpty)
    {{-- EMPTY STATE --}}
    <div class="kontribusi-empty-state">
        <i data-lucide="pie-chart" style="width:40px;height:40px;color:#94a3b8;"></i>
        <div class="kontribusi-empty-title">Belum ada data penjualan</div>
        <div class="kontribusi-empty-desc">
            Data akan muncul setelah admin menginputkan transaksi penjualan cabang.
        </div>
    </div>

    @else

    <div class="kontribusi-grid">

        {{-- CARD UTAMA --}}
        <div class="kontribusi-card-main">

            <div class="kontribusi-card-top">
                <div>
                    <div class="kontribusi-card-title">Kontribusi Penjualan per Toko</div>
                    <div class="kontribusi-card-subtitle">Distribusi penjualan berdasarkan periode</div>
                </div>
                <form method="GET" action="{{ route('owner.kontribusi-toko') }}">
                    <select name="tahun" onchange="this.form.submit()" class="kontribusi-select">
                        @foreach($tahunList as $itemTahun)
                            <option value="{{ $itemTahun }}" {{ $tahun == $itemTahun ? 'selected' : '' }}>
                                {{ $itemTahun }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- DOUGHNUT CHART --}}
            <div class="kontribusi-chart-area">
                <div class="kontribusi-chart-wrap">
                    <canvas id="edasChart"></canvas>
                    <div class="kontribusi-chart-center">
                        <div class="kontribusi-chart-center-num">{{ $jumlahCabang }}</div>
                        <div class="kontribusi-chart-center-label">Toko Aktif</div>
                    </div>
                </div>
            </div>

            {{-- LEGEND + RANKING --}}
            <div class="kontribusi-legend">
                @foreach($data as $i => $item)
                @php
                    $palette = ['#314cff','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899','#84cc16'];
                    $color = $palette[$i % count($palette)];
                @endphp
                <div class="kontribusi-legend-row">
                    <div class="kontribusi-legend-left">
                        <span class="kontribusi-legend-dot" style="background:{{ $color }};"></span>
                        <span class="kontribusi-legend-name">{{ $item->shopping_mall }}</span>
                    </div>
                    <div class="kontribusi-legend-right">
                        <span class="kontribusi-legend-pct">{{ number_format($item->persentase, 1) }}%</span>
                        <span class="kontribusi-legend-val">Rp{{ number_format($item->total_sales, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach

                <div class="kontribusi-legend-total">
                    <span>Total</span>
                    <div class="kontribusi-legend-right">
                        <span class="kontribusi-legend-pct">100%</span>
                        <span class="kontribusi-legend-val">Rp{{ number_format($totalSales, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- SIDEBAR KANAN --}}
        <div class="kontribusi-sidebar">

            {{-- Kontribusi Terbesar --}}
            <div class="kontribusi-side-card">
                <div class="kontribusi-side-card-title">Kontribusi Terbesar</div>
                <div class="kontribusi-side-card-body">
                    <div class="kontribusi-side-icon blue-icon">
                        <i data-lucide="trending-up" style="width:20px;height:20px;color:#314cff;"></i>
                    </div>
                    <div class="kontribusi-side-info">
                        <div class="kontribusi-side-store">{{ $best->shopping_mall }}</div>
                        <div class="kontribusi-side-pct blue">{{ number_format($best->persentase, 1) }}%</div>
                    </div>
                </div>
                <div class="kontribusi-side-sales">Rp{{ number_format($best->total_sales, 0, ',', '.') }}</div>
                <div class="kontribusi-progress-track">
                    <div class="kontribusi-progress-fill blue"
                        style="width:{{ number_format($best->persentase, 1) }}%;"></div>
                </div>
            </div>

            {{-- Kontribusi Terkecil --}}
            <div class="kontribusi-side-card">
                <div class="kontribusi-side-card-title">Kontribusi Terkecil</div>
                <div class="kontribusi-side-card-body">
                    <div class="kontribusi-side-icon gray-icon">
                        <i data-lucide="trending-down" style="width:20px;height:20px;color:#64748b;"></i>
                    </div>
                    <div class="kontribusi-side-info">
                        <div class="kontribusi-side-store">{{ $worst->shopping_mall }}</div>
                        <div class="kontribusi-side-pct gray">{{ number_format($worst->persentase, 1) }}%</div>
                    </div>
                </div>
                <div class="kontribusi-side-sales">Rp{{ number_format($worst->total_sales, 0, ',', '.') }}</div>
                <div class="kontribusi-progress-track">
                    <div class="kontribusi-progress-fill gray"
                        style="width:{{ number_format($worst->persentase, 1) }}%;"></div>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="kontribusi-side-card">
                <div class="kontribusi-side-card-title">Ringkasan</div>
                <div class="kontribusi-summary-list">
                    <div class="kontribusi-summary-row">
                        <span class="kontribusi-summary-label">Total Penjualan</span>
                        <span class="kontribusi-summary-value">
                            Rp{{ number_format($totalSales, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="kontribusi-summary-row">
                        <span class="kontribusi-summary-label">Jumlah Cabang</span>
                        <span class="kontribusi-summary-value">{{ $jumlahCabang }} cabang</span>
                    </div>
                    <div class="kontribusi-summary-row last">
                        <span class="kontribusi-summary-label">Rata-rata per Cabang</span>
                        <span class="kontribusi-summary-value">
                            Rp{{ number_format($rataRataCabang, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    @if(!$isEmpty)
    const labels  = @json($chartLabels);
    const sales   = @json($chartSales);
    const colors  = @json($chartColors);

    // Hitung total untuk persentase
    const total = sales.reduce((a, b) => a + b, 0);

    Chart.register(ChartDataLabels);

    new Chart(document.getElementById('edasChart'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: sales,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const pct = ((ctx.raw / total) * 100).toFixed(1);
                            return ctx.label + ': ' + pct + '% (Rp' + ctx.raw.toLocaleString('id-ID') + ')';
                        }
                    }
                },
                datalabels: {
                    color: '#ffffff',
                    font: {
                        weight: 'bold',
                        size: 12,
                        family: "'Plus Jakarta Sans', sans-serif",
                    },
                    formatter: function(value, ctx) {
                        const pct = ((value / total) * 100);
                        // Sembunyikan label jika segmen terlalu kecil (< 4%)
                        if (pct < 4) return '';
                        return pct.toFixed(1) + '%';
                    },
                    // Posisi label di tengah segmen
                    anchor: 'center',
                    align: 'center',
                    offset: 0,
                    // Shadow agar teks terbaca di semua warna
                    textShadowBlur: 4,
                    textShadowColor: 'rgba(0,0,0,0.3)',
                }
            }
        }
    });
    @endif
});
</script>

@endsection