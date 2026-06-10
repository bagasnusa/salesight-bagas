@extends('layouts.owner')

@section('content')

    <div class="tren-global-content">

        <div class="tren-global-header">
            <div class="tren-global-title">Tren Penjualan Global</div>
            <div class="tren-global-subtitle">Analisis tren penjualan keseluruhan semua cabang</div>
        </div>

        <div class="tren-global-chart-card">
            <div class="tren-global-chart-header">
                <div class="tren-global-card-title">Grafik Tren Tahunan</div>
                <form method="GET" action="{{ route('owner.tren-global') }}">
                    <select name="tahun" onchange="this.form.submit()" class="tren-global-year-select">
                        @foreach($tahunList as $itemTahun)
                            <option value="{{ $itemTahun }}" {{ $tahun == $itemTahun ? 'selected' : '' }}>
                                Tahun {{ $itemTahun }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="tren-global-chart-wrapper">
                <canvas id="globalSalesChart"></canvas>
            </div>
        </div>

        <div class="tren-global-bottom-cards">

            {{-- KARTU PERBANDINGAN --}}
            <div class="tren-global-info-card">
                <div class="tren-global-info-title">Perbandingan Penjualan</div>

                {{-- Toggle Per Bulan / Per Tahun --}}
                <div class="perbandingan-toggle">
                    <button class="toggle-btn active" onclick="switchMode('bulan', this)">Per Bulan</button>
                    <button class="toggle-btn" onclick="switchMode('tahun', this)">Per Tahun</button>
                </div>

                {{-- MODE: Per Bulan --}}
                <div id="mode-bulan">
                    <div class="tren-global-comparison active">
                        {{-- SEBELUM: Bulan Ini — {{ $labelBulanIni }} --}}
                        <div class="tren-global-comparison-title">{{ $labelKartuIni }} — {{ $labelBulanIni }}</div>
                        <div class="tren-global-comparison-value">
                            Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="tren-global-comparison">
                        {{-- SEBELUM: Bulan Lalu — {{ $labelBulanLalu }} --}}
                        <div class="tren-global-comparison-title gray">{{ $labelKartuLalu }} — {{ $labelBulanLalu }}</div>
                        <div class="tren-global-comparison-value">
                            Rp {{ number_format($penjualanBulanLalu, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="tren-global-alert">
                        <div class="tren-global-alert-text">
                            <i data-lucide="info" ...></i>
                            {{ $statusBulan }} {{ number_format(abs($persentaseBulan), 1) }}% dibanding
                            {{ $labelKartuLalu == 'Bulan Lalu' ? 'bulan sebelumnya' : $labelBulanLalu }}
                        </div>
                    </div>
                </div>

                {{-- MODE: Per Tahun --}}
                <div id="mode-tahun" style="display:none;">
                    <div class="tren-global-comparison active">
                        <div class="tren-global-comparison-title">Tahun Dipilih — {{ $tahun }}</div>
                        <div class="tren-global-comparison-value">
                            Rp {{ number_format($totalTahunIni, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="tren-global-comparison">
                        <div class="tren-global-comparison-title gray">Tahun Sebelumnya — {{ (int) $tahun - 1 }}</div>
                        <div class="tren-global-comparison-value">
                            Rp {{ number_format($totalTahunLalu, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="tren-global-alert">
                        <div class="tren-global-alert-text">
                            <i data-lucide="info"
                                style="width:16px; height:16px; display:inline-block; vertical-align:middle; margin-right:4px;"></i>
                            {{ $statusTahun }} {{ number_format(abs($persentaseTahun), 1) }}% dibanding
                            {{ (int) $tahun - 1 }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU INSIGHT --}}
            <div class="tren-global-info-card">
                <div class="tren-global-info-title">Insight Penjualan ({{ $tahun }})</div>

                <div class="tren-global-insight warning">
                    <div class="tren-global-insight-top">
                        <div class="tren-global-icon orange">
                            <i data-lucide="trending-up" style="color: white; width: 20px; height: 20px;"></i>
                        </div>
                        <div class="tren-global-insight-text orange-text">Bulan Penjualan Tertinggi</div>
                    </div>
                    <div class="tren-global-insight-detail">
                        <div class="tren-global-insight-month">{{ $labelTertinggi }}</div>
                        <div class="tren-global-insight-sales">Rp{{ number_format($nilaiTertinggi, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="tren-global-insight danger">
                    <div class="tren-global-insight-top">
                        <div class="tren-global-icon red">
                            <i data-lucide="trending-down" style="color: white; width: 20px; height: 20px;"></i>
                        </div>
                        <div class="tren-global-insight-text red-text">Bulan Penjualan Terendah</div>
                    </div>
                    <div class="tren-global-insight-detail">
                        <div class="tren-global-insight-month">{{ $labelTerendah }}</div>
                        <div class="tren-global-insight-sales">Rp{{ number_format($nilaiTerendah, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="tren-global-average">
                    <div class="tren-global-average-title">Growth Bulanan (%)</div>
                    <div class="tren-global-average-grid">
                        @forelse($growthBulanan as $growth)
                            <div
                                class="growth-badge {{ $growth['growth'] > 0 ? 'positive' : ($growth['growth'] < 0 ? 'negative' : 'neutral') }}">
                                <span class="month">{{ $growth['bulan'] }}</span>
                                <span class="val">{{ $growth['growth'] > 0 ? '+' : '' }}{{ $growth['growth'] }}%</span>
                            </div>
                        @empty
                            <span style="color:#94a3b8; font-size:12px; grid-column: span 4;">Belum ada tren pertumbuhan</span>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>

    <style>
        .perbandingan-toggle {
            display: flex;
            gap: 4px;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 14px;
        }

        .toggle-btn {
            flex: 1;
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            background: transparent;
            color: #64748b;
            font-weight: 500;
            transition: all 0.18s;
        }

        .toggle-btn.active {
            background: #ffffff;
            color: #314cff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 0.5px solid #e2e8f0;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function switchMode(mode, btn) {
            document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('mode-bulan').style.display = mode === 'bulan' ? 'block' : 'none';
            document.getElementById('mode-tahun').style.display = mode === 'tahun' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const canvas = document.getElementById('globalSalesChart');

            const labels = {!! json_encode($chartLabels) !!};
            const salesData = {!! json_encode($chartSales) !!};

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Penjualan',
                        data: salesData,
                        borderColor: '#314cff',
                        backgroundColor: 'rgba(49, 76, 255, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#314cff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            border: { display: false },
                            ticks: {
                                callback: function (value) {
                                    if (value >= 1000000) return 'Rp' + (value / 1000000) + 'Jt';
                                    if (value >= 1000) return 'Rp' + (value / 1000) + 'Rb';
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false }
                        }
                    }
                }
            });
        });
    </script>

@endsection