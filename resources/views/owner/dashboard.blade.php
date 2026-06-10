@extends('layouts.owner')

@section('content')

<div class="dashboard-content">

    <div class="dashboard-overview-section">

        <div class="dashboard-sec-header">
            <div class="dashboard-title-group">
                <div class="dashboard-heading">
                    <div class="dashboard-title">
                        Dashboard Overview
                    </div>
                </div>

                <div class="dashboard-subtitle-wrapper">
                    <div class="dashboard-subtitle">
                        Ringkasan performa bisnis multi-cabang
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-cards">

            <div class="dashboard-card">
                <div class="dashboard-card-top">
                    <div class="dashboard-card-text">Total Penjualan (Tahun)</div>
                    <div class="dashboard-card-icon"><img src="{{ asset('img/Container1.png') }}" alt=""></div>
                </div>
                <div class="dashboard-card-bottom">
                    <h3 class="dashboard-value">Rp {{ number_format($totalPenjualanTahun, 0, ',', '.') }}</h3>
                    
                    @if($totalPenjualanTahun > 0)
                        <div class="dashboard-trend up"><i data-lucide="trending-up"></i> +12.5%</div>
                    @else
                        <div class="dashboard-trend neutral"><i data-lucide="minus"></i> 0%</div>
                    @endif
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-top">
                    <div class="dashboard-card-text">Total Transaksi</div>
                    <div class="dashboard-card-icon green"><img src="{{ asset('img/Container2.png') }}" alt=""></div>
                </div>
                <div class="dashboard-card-bottom">
                    <h3 class="dashboard-value">{{ number_format($totalTransaksi, 0, ',', '.') }}</h3>
                    
                    @if($totalTransaksi > 0)
                        <div class="dashboard-trend up"><i data-lucide="trending-up"></i> +5.2%</div>
                    @else
                        <div class="dashboard-trend neutral"><i data-lucide="minus"></i> 0%</div>
                    @endif
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-top">
                    <div class="dashboard-card-text">Total Cabang</div>
                    <div class="dashboard-card-icon"><img src="{{ asset('img/Container3.png') }}" alt=""></div>
                </div>
                <div class="dashboard-card-bottom">
                    <h3 class="dashboard-value">{{ $totalCabang }}</h3>
                    <div class="dashboard-trend neutral"><i data-lucide="minus"></i> Tetap</div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-top">
                    <div class="dashboard-card-text">Rata-rata Harian</div>
                    <div class="dashboard-card-icon purple"><img src="{{ asset('img/Container4.png') }}" alt=""></div>
                </div>
                <div class="dashboard-card-bottom">
                    <h3 class="dashboard-value">Rp {{ number_format($rataRataHarian, 0, ',', '.') }}</h3>
                    <div class="dashboard-trend neutral"><i data-lucide="minus"></i> -</div>
                </div>
            </div>

        </div>

        <div class="dashboard-stats">

            <div class="dashboard-small-card">
                <div class="dashboard-small-title">Omset Bulan Ini</div>
                <div class="dashboard-small-value">
                    Rp {{ number_format($omsetBulanIni, 0, ',', '.') }}
                </div>
            </div>

            <div class="dashboard-small-card">
                <div class="dashboard-small-title">Omset Tahun Ini</div>
                <div class="dashboard-small-value">
                    Rp {{ number_format($totalPenjualanTahun, 0, ',', '.') }}
                </div>
            </div>

            <div class="dashboard-small-card">
                <div class="dashboard-small-title">Rata-rata per Transaksi</div>
                <div class="dashboard-small-value">
                    Rp {{ number_format($rataRataPerTransaksi, 0, ',', '.') }}
                </div>
            </div>

        </div>

        <div class="dashboard-chart-card">

            <div class="dashboard-chart-header">
                <div>
                    <div class="dashboard-chart-title">
                        Grafik Penjualan
                    </div>
                    <div class="dashboard-chart-subtitle">
                        Total penjualan semua cabang
                    </div>
                </div>

                <div class="dashboard-filter">
                    <button class="dashboard-filter-btn">Mingguan</button>
                    <button class="dashboard-filter-btn active">Bulanan</button>
                    <button class="dashboard-filter-btn">Tahunan</button>
                </div>
            </div>

            <div id="salesChart" style="min-height: 350px;"></div>

        </div>

    </div>

</div>

<script>
    // Memastikan icon lucide ter-render
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const filterButtons = document.querySelectorAll('.dashboard-filter-btn');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // hapus active dari semua tombol
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            // tambahkan active ke tombol yang diklik
            button.classList.add('active');
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // 1. Script khusus untuk Tombol Filter & Lucide Icon (Diisolasi agar tidak mengganggu grafik)
    try {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const filterButtons = document.querySelectorAll('.dashboard-filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    } catch (e) {
        console.log("Lucide/Filter error ignored:", e);
    }
</script>

<script>
    // 2. Script khusus untuk Render Grafik (Dibuat mandiri tanpa bungkus DOMContentLoaded agar langsung jalan)
    try {
        // Ambil data dari controller, jika kosong otomatis ganti ke array 0 sebanyak 12 bulan
        var chartData = {!! json_encode($chartData ?? array_fill(0, 12, 0)) !!};

        var options = {
            series: [{
                name: 'Total Penjualan (Rp)',
                data: chartData
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                zoom: { enabled: false }
            },
            colors: ['#314cff'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        if(value >= 1000000) return "Rp" + (value / 1000000).toFixed(1) + "Jt";
                        if(value >= 1000) return "Rp" + (value / 1000).toFixed(0) + "Rb";
                        return "Rp" + value;
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toLocaleString('id-ID');
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#salesChart"), options);
        chart.render();
    } catch (error) {
        console.error("ApexCharts Render Error:", error);
    }
</script>

@endsection