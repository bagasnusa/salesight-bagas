<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salesight - Monitor Your Sales Performance</title>
    
    <!-- Tailwind CSS (v4) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        
        /* Tema Warna Custom */
        :root {
            --brand-blue: #3B52FF;
            --brand-dark: #0B1120;
        }

        .text-brand-blue { color: var(--brand-blue); }
        .bg-brand-blue { background-color: var(--brand-blue); }
        .bg-brand-dark { background-color: var(--brand-dark); }
        .border-brand-blue { border-color: var(--brand-blue); }

        /* Initial State Animasi (Tersembunyi di awal) */
        .chart-line {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
        }
        .chart-area {
            opacity: 0;
        }
        .bar-grow {
            /* Penting agar bar tumbuh dari bawah (bottom) elemennya sendiri */
            transform-box: fill-box;
            transform-origin: bottom;
            transform: scaleY(0);
        }

        /* Trigger Animasi (Hanya jalan kalau ada class .play-anim dari JS) */
        .play-anim .chart-line {
            animation: drawLine 2.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .play-anim .chart-area {
            animation: fadeInArea 2s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards;
        }
        .play-anim .bar-grow {
            animation: growBar 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        
        /* Delay untuk animasi balok (Bar Chart) */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        .delay-700 { animation-delay: 0.7s; }

        /* Keyframes */
        @keyframes drawLine {
            to { stroke-dashoffset: 0; }
        }
        @keyframes fadeInArea {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes growBar {
            to { transform: scaleY(1); }
        }

        /* Float Animation untuk Hero Card */
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased scroll-smooth">

    <!-- NAVBAR -->
    <nav class="bg-white sticky top-0 z-50 border-b border-slate-100/50 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-brand-blue rounded-xl flex items-center justify-center">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 text-white"></i>
                </div>
                <span class="text-xl font-extrabold text-slate-900 tracking-tight">Salesight</span>
            </a>
            
            <!-- Links -->
            <div class="hidden md:flex items-center space-x-8 font-semibold text-[15px] text-slate-500">
                <a href="#" class="text-slate-900 hover:text-brand-blue transition">Home</a>
                <a href="#fitur" class="hover:text-brand-blue transition">Features</a>
                <a href="#workflow" class="hover:text-brand-blue transition">How It Works</a>
            </div>

            <!-- Button -->
            <div>
                <a href="/register" class="bg-brand-blue hover:bg-blue-700 text-white text-[15px] font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/30 inline-block">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="max-w-7xl mx-auto px-6 py-20 md:py-28 grid md:grid-cols-2 gap-12 items-center">
        <!-- Left Content -->
        <div class="space-y-6">
            <div class="inline-flex items-center gap-2 bg-blue-50/80 text-brand-blue px-3 py-1.5 rounded-full border border-blue-100">
                <i data-lucide="trending-up" class="w-4 h-4"></i>
                <span class="text-xs font-bold tracking-wide">Real-Time Sales Analytics</span>
            </div>
            
            <h1 class="text-5xl md:text-[54px] font-black tracking-tight leading-[1.15] text-slate-900">
                Pantau performa penjualan anda secara <span class="text-brand-blue">Real-Time</span>
            </h1>
            
            <p class="text-slate-500 text-lg leading-relaxed max-w-lg">
                Kelola data penjualan dengan mudah dan dapatkan insight secara otomatis melalui dashboard.
            </p>
            
            <div class="flex items-center gap-4 pt-4">
                <a href="/register" class="bg-brand-blue hover:bg-blue-700 text-white font-bold px-7 py-3.5 rounded-xl transition shadow-xl shadow-blue-500/30 flex items-center gap-2">
                    Get Started <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
                <a href="#demo" class="bg-white border-2 border-slate-200 hover:border-slate-300 text-slate-700 font-bold px-7 py-3.5 rounded-xl transition">
                    View Demo
                </a>
            </div>
        </div>

        <!-- Right Content (Floating Card) -->
        <div class="relative flex justify-center md:justify-end">
            <!-- Decorative Background Blur -->
            <div class="absolute inset-0 bg-blue-400/20 blur-3xl rounded-full transform scale-90 translate-y-10 -z-10"></div>
            
            <!-- Tambahkan class observe-anim agar terdeteksi scroll -->
            <div class="bg-white p-7 rounded-[24px] shadow-2xl border border-slate-100 w-full max-w-[460px] animate-float relative z-10 observe-anim">
                
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-brand-blue rounded-md flex items-center justify-center">
                            <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-white"></i>
                        </div>
                        <span class="font-bold text-slate-900 text-sm">Dashboard Penjualan</span>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md">Apr 2025</span>
                </div>

                <p class="text-xs text-slate-400 font-medium mb-1">Total Pendapatan Bulan Ini</p>
                <div class="flex items-center gap-3 mb-6">
                    <h3 class="text-3xl font-black text-slate-900">Rp45.280.000</h3>
                    <div class="bg-green-100 text-green-600 px-2 py-1 rounded-md flex items-center gap-1 text-[11px] font-bold">
                        <i data-lucide="trending-up" class="w-3 h-3"></i> +12.5%
                    </div>
                </div>
                
                <!-- Animated Mini Chart -->
                <div class="h-20 w-full mb-6 relative">
                    <svg viewBox="0 0 400 80" class="w-full h-full overflow-visible">
                        <defs>
                            <linearGradient id="miniGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#3B52FF" stop-opacity="0.15"/>
                                <stop offset="100%" stop-color="#3B52FF" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <path class="chart-area" d="M0 60 C50 65, 100 50, 150 45 S250 35, 300 40 S350 30, 400 25 L400 80 L0 80 Z" fill="url(#miniGradient)"/>
                        <path class="chart-line" d="M0 60 C50 65, 100 50, 150 45 S250 35, 300 40 S350 30, 400 25" fill="none" stroke="#3B52FF" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    <!-- X Axis Labels -->
                    <div class="flex justify-between text-[10px] text-slate-300 font-semibold uppercase mt-2 px-2">
                        <span>Des</span><span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span>
                    </div>
                </div>

                <!-- Bottom Stats -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="border border-slate-100 rounded-xl p-3">
                        <h4 class="text-sm font-black text-slate-900">1.240</h4>
                        <p class="text-[10px] font-medium text-slate-400 mt-0.5">Transaksi</p>
                    </div>
                    <div class="border border-slate-100 rounded-xl p-3">
                        <h4 class="text-sm font-black text-slate-900">34</h4>
                        <p class="text-[10px] font-medium text-slate-400 mt-0.5">Produk</p>
                    </div>
                    <div class="border border-slate-100 rounded-xl p-3">
                        <h4 class="text-sm font-black text-slate-900">Rp36.5K</h4>
                        <p class="text-[10px] font-medium text-slate-400 mt-0.5">Rata-rata</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- PROBLEM SECTION -->
    <section class="bg-[#FAFAFC] py-24 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6 text-center">
            
            <div class="inline-flex items-center gap-1.5 bg-red-50 text-red-500 px-3 py-1 rounded-full text-xs font-bold mb-6">
                <i data-lucide="triangle-alert" class="w-3.5 h-3.5 fill-red-500 text-white"></i> Masalah yang Sering Terjadi
            </div>
            
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Kesulitan mengelola data penjualan?</h2>
            <p class="text-slate-500 text-[15px] max-w-2xl mx-auto mb-16">Banyak bisnis menghadapi tantangan ini setiap harinya. Anda tidak sendirian.</p>

            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-6 text-left">
                <!-- Card 1 -->
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center mb-6">
                        <i data-lucide="file-x-2" class="w-6 h-6 text-red-500"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-3 leading-snug">Data Tidak Terstruktur</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Data penjualan tersebar di berbagai tempat dan format yang berbeda-beda sehingga sulit dikelola.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-6">
                        <i data-lucide="clock-4" class="w-6 h-6 text-amber-500"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-3 leading-snug">Laporan Memakan Waktu</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Membuat laporan penjualan secara manual membutuhkan waktu yang sangat lama dan rawan kesalahan.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center mb-6">
                        <i data-lucide="eye" class="w-6 h-6 text-purple-500"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-3 leading-snug">Sulit Membaca Tren</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Tanpa visualisasi yang tepat, sangat sulit untuk memahami tren dan pola penjualan bisnis Anda.</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-6">
                        <i data-lucide="wifi-off" class="w-6 h-6 text-slate-500"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-3 leading-snug">Tidak Real-Time</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Data penjualan tidak terupdate secara langsung sehingga pengambilan keputusan menjadi lambat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURE SECTION -->
    <section id="fitur" class="py-24">
        <div class="max-w-7xl mx-auto px-6 text-center">
            
            <div class="inline-flex items-center gap-1.5 bg-blue-50 text-brand-blue px-3 py-1 rounded-full text-xs font-bold mb-6 border border-blue-100">
                ✨ Fitur Unggulan
            </div>
            
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Solusi cerdas untuk analisis penjualan</h2>
            <p class="text-slate-500 text-[15px] max-w-2xl mx-auto mb-16">Semua yang Anda butuhkan untuk mengelola dan menganalisis penjualan dalam satu platform terintegrasi.</p>

            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-6 text-left">
                <!-- F1 -->
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm hover:border-blue-200 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-brand-blue shadow-lg shadow-blue-500/30 flex items-center justify-center mb-6 group-hover:scale-105 transition">
                        <i data-lucide="database" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-[10px] font-bold text-brand-blue bg-blue-50 px-2 py-1 rounded mb-3 inline-block">Admin</span>
                    <h4 class="font-bold text-slate-900 text-[17px] mb-3 leading-snug">Input Data Mudah</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Admin dapat memasukkan data transaksi penjualan dengan mudah melalui form yang intuitif dan terstruktur.</p>
                </div>
                <!-- F2 -->
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm hover:border-blue-200 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-brand-blue shadow-lg shadow-blue-500/30 flex items-center justify-center mb-6 group-hover:scale-105 transition">
                        <i data-lucide="layout-grid" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-[10px] font-bold text-brand-blue bg-blue-50 px-2 py-1 rounded mb-3 inline-block">Automated</span>
                    <h4 class="font-bold text-slate-900 text-[17px] mb-3 leading-snug">Dashboard Otomatis</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Sistem otomatis memproses dan menampilkan data dalam bentuk visualisasi yang informatif dan mudah dipahami.</p>
                </div>
                <!-- F3 -->
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm hover:border-blue-200 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-brand-blue shadow-lg shadow-blue-500/30 flex items-center justify-center mb-6 group-hover:scale-105 transition">
                        <i data-lucide="zap" class="w-6 h-6 text-white fill-white"></i>
                    </div>
                    <span class="text-[10px] font-bold text-brand-blue bg-blue-50 px-2 py-1 rounded mb-3 inline-block">Real-Time</span>
                    <h4 class="font-bold text-slate-900 text-[17px] mb-3 leading-snug">Real-Time Analytics</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Pantau performa penjualan secara langsung dengan data yang selalu terupdate secara real-time.</p>
                </div>
                <!-- F4 -->
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm hover:border-blue-200 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-brand-blue shadow-lg shadow-blue-500/30 flex items-center justify-center mb-6 group-hover:scale-105 transition">
                        <i data-lucide="lightbulb" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-[10px] font-bold text-brand-blue bg-blue-50 px-2 py-1 rounded mb-3 inline-block">Insights</span>
                    <h4 class="font-bold text-slate-900 text-[17px] mb-3 leading-snug">Business Insights</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Dapatkan insight bisnis yang berharga untuk membantu pengambilan keputusan yang lebih baik dan tepat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- WORKFLOW SECTION -->
    <section id="workflow" class="bg-[#F8F9FF] py-24 border-y border-blue-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            
            <div class="inline-flex items-center gap-1.5 bg-white text-brand-blue px-3 py-1 rounded-full text-xs font-bold mb-6 border border-blue-100 shadow-sm">
                <i data-lucide="git-merge" class="w-3.5 h-3.5"></i> Cara Kerja
            </div>
            
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Bagaimana Salesight Bekerja?</h2>
            <p class="text-slate-500 text-[15px] max-w-2xl mx-auto mb-16">Tiga langkah mudah untuk mulai menganalisis data penjualan Anda secara profesional.</p>

            <div class="grid md:grid-cols-3 gap-8 relative items-center">
                
                <!-- Arrow Decorators (Desktop Only) -->
                <div class="hidden md:block absolute top-1/2 left-[30%] -translate-y-1/2 z-0">
                    <i data-lucide="chevron-right" class="w-8 h-8 text-blue-300"></i>
                </div>
                <div class="hidden md:block absolute top-1/2 left-[64%] -translate-y-1/2 z-0">
                    <i data-lucide="chevron-right" class="w-8 h-8 text-blue-300"></i>
                </div>

                <!-- Step 1 -->
                <div class="bg-white p-10 rounded-[24px] border border-slate-100 shadow-sm text-center relative z-10">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                        <i data-lucide="database" class="w-8 h-8 text-brand-blue"></i>
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-brand-blue text-white rounded-full flex items-center justify-center text-xs font-bold border-2 border-white shadow-sm">1</div>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-3">Input Data Transaksi</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Masukkan data transaksi penjualan harian secara mudah melalui form yang telah disediakan oleh sistem.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-10 rounded-[24px] border border-slate-100 shadow-sm text-center relative z-10">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                        <i data-lucide="zap" class="w-8 h-8 text-brand-blue"></i>
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-brand-blue text-white rounded-full flex items-center justify-center text-xs font-bold border-2 border-white shadow-sm">2</div>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-3">Sistem Mengolah Data</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Sistem akan secara otomatis mengolah, menghitung, dan menganalisis seluruh data yang telah diinput.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-10 rounded-[24px] border border-slate-100 shadow-sm text-center relative z-10">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                        <i data-lucide="layout-grid" class="w-8 h-8 text-brand-blue"></i>
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-brand-blue text-white rounded-full flex items-center justify-center text-xs font-bold border-2 border-white shadow-sm">3</div>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-3">Dashboard Ditampilkan</h4>
                    <p class="text-slate-500 text-[13px] leading-relaxed">Lihat hasil analisis dalam bentuk visualisasi data yang informatif dan mudah dipahami di dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- DASHBOARD PREVIEW SECTION -->
    <section id="demo" class="bg-brand-dark text-white pt-24 pb-32">
        <div class="max-w-[1000px] mx-auto px-6 text-center">
            
            <div class="inline-flex items-center gap-1.5 bg-slate-800 text-blue-300 px-3 py-1 rounded-full text-xs font-bold mb-6 border border-slate-700">
                📊 Tampilan Dashboard
            </div>
            
            <h2 class="text-3xl md:text-[40px] font-black mt-2 mb-4 tracking-tight">Lihat Dashboard Secara Langsung</h2>
            <p class="text-slate-400 text-[15px] max-w-2xl mx-auto mb-14">Semua data penjualan Anda dalam satu tampilan komprehensif yang mudah dipahami.</p>

            <!-- BIG DASHBOARD MOCKUP -->
            <!-- Tambahkan class observe-anim di sini agar animasi terpicu saat di-scroll -->
            <div class="bg-white text-slate-800 p-8 md:p-10 rounded-[24px] shadow-[0_20px_50px_rgba(0,0,0,0.5)] text-left w-full mx-auto relative overflow-hidden observe-anim">
                
                <!-- Header Mockup -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-brand-blue rounded-lg flex items-center justify-center">
                            <i data-lucide="bar-chart-2" class="w-4 h-4 text-white"></i>
                        </div>
                        <h3 class="font-black text-xl text-slate-900">Dashboard Penjualan</h3>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md ml-1">Apr 2025</span>
                    </div>
                    
                    <div class="flex items-center bg-slate-50 p-1 rounded-lg border border-slate-100">
                        <button class="px-4 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition rounded-md">Harian</button>
                        <button class="px-4 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition rounded-md border-x border-slate-200">Mingguan</button>
                        <button class="px-4 py-1.5 text-xs font-bold bg-brand-blue text-white shadow-sm rounded-md transition">Bulanan</button>
                    </div>
                </div>

                <!-- 4 Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-xs text-slate-400 font-medium mb-1">Total Pendapatan</p>
                        <h4 class="text-xl font-black text-slate-900 mb-2">Rp45.280.000</h4>
                        <div class="flex items-center gap-1 text-[10px] font-bold text-green-600">
                            <i data-lucide="trending-up" class="w-3 h-3"></i> +12.5% <span class="text-slate-400 font-medium ml-1">vs bulan lalu</span>
                        </div>
                    </div>
                    <div class="p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-xs text-slate-400 font-medium mb-1">Total Transaksi</p>
                        <h4 class="text-xl font-black text-slate-900 mb-2">1.240</h4>
                        <div class="flex items-center gap-1 text-[10px] font-bold text-green-600">
                            <i data-lucide="trending-up" class="w-3 h-3"></i> +8.3% <span class="text-slate-400 font-medium ml-1">vs bulan lalu</span>
                        </div>
                    </div>
                    <div class="p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-xs text-slate-400 font-medium mb-1">Produk Aktif</p>
                        <h4 class="text-xl font-black text-slate-900 mb-2">34</h4>
                        <div class="flex items-center gap-1 text-[10px] font-bold text-green-600">
                            <i data-lucide="trending-up" class="w-3 h-3"></i> +2.9% <span class="text-slate-400 font-medium ml-1">vs bulan lalu</span>
                        </div>
                    </div>
                    <div class="p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-xs text-slate-400 font-medium mb-1">Rata-rata/Transaksi</p>
                        <h4 class="text-xl font-black text-slate-900 mb-2">Rp36.516</h4>
                        <div class="flex items-center gap-1 text-[10px] font-bold text-red-500">
                            <i data-lucide="trending-down" class="w-3 h-3"></i> -1.2% <span class="text-slate-400 font-medium ml-1">vs bulan lalu</span>
                        </div>
                    </div>
                </div>

                <!-- Main Line Chart (Pendapatan Bulanan) -->
                <div class="p-6 rounded-2xl border border-slate-100 shadow-sm mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="font-bold text-slate-900 text-sm">Pendapatan Bulanan</h4>
                        <span class="text-xs text-slate-400 font-medium">Jan – Des 2025</span>
                    </div>
                    
                    <div class="relative h-48 w-full">
                        <!-- Y-Axis Grid & Labels -->
                        <div class="absolute inset-0 flex flex-col justify-between text-[10px] text-slate-300 font-medium pb-6 z-0">
                            <div class="w-full border-b border-slate-100/60 flex items-center justify-between pb-1"><span>80jt</span></div>
                            <div class="w-full border-b border-slate-100/60 flex items-center justify-between pb-1"><span>60jt</span></div>
                            <div class="w-full border-b border-slate-100/60 flex items-center justify-between pb-1"><span>40jt</span></div>
                            <div class="w-full border-b border-slate-100/60 flex items-center justify-between pb-1"><span>20jt</span></div>
                            <div class="w-full border-b border-slate-100/60 flex items-center justify-between pb-1"><span>0jt</span></div>
                        </div>

                        <!-- SVG Animated Area Chart -->
                        <div class="absolute inset-0 pl-6 pb-6 z-10">
                            <svg viewBox="0 0 800 150" class="w-full h-full overflow-visible" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="mainGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" stop-color="#3B52FF" stop-opacity="0.15"/>
                                        <stop offset="100%" stop-color="#3B52FF" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <!-- The path data covers 12 points for Jan-Dec -->
                                <path class="chart-area" d="M0 110 C 50 100, 100 115, 150 80 S 250 85, 300 100 S 400 60, 450 65 S 550 50, 600 55 S 700 30, 800 20 L 800 150 L 0 150 Z" fill="url(#mainGradient)"/>
                                <path class="chart-line" d="M0 110 C 50 100, 100 115, 150 80 S 250 85, 300 100 S 400 60, 450 65 S 550 50, 600 55 S 700 30, 800 20" fill="none" stroke="#3B52FF" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <!-- X-Axis Labels -->
                        <div class="absolute bottom-0 left-6 right-0 flex justify-between text-[10px] text-slate-400 font-medium">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span><span>Jul</span><span>Agu</span><span>Sep</span><span>Okt</span><span>Nov</span><span>Des</span>
                        </div>
                    </div>
                </div>

                <!-- Secondary Bar Chart (Tren Mingguan - FULL WIDTH) -->
                <div class="p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h4 class="font-bold text-slate-900 text-sm mb-6">Tren Mingguan</h4>
                    
                    <div class="relative h-40 w-full">
                        <!-- Y-Axis -->
                        <div class="absolute inset-0 flex flex-col justify-between text-[10px] text-slate-300 font-medium pb-6 z-0">
                            <div class="w-full flex items-center justify-between"><span>24jt</span></div>
                            <div class="w-full flex items-center justify-between"><span>18jt</span></div>
                            <div class="w-full flex items-center justify-between"><span>12jt</span></div>
                            <div class="w-full flex items-center justify-between"><span>6jt</span></div>
                            <div class="w-full flex items-center justify-between"><span>0jt</span></div>
                        </div>

                        <!-- SVG Animated Bar Chart -->
                        <div class="absolute inset-0 pl-8 pr-2 pb-6 z-10 flex justify-between items-end">
                            <svg viewBox="0 0 700 120" class="w-full h-full" preserveAspectRatio="none">
                                <!-- Bars growing from bottom using CSS animation. Menggunakan fill="#3B52FF" langsung untuk memecahkan warna hitam -->
                                <rect x="3%" y="70" width="8%" height="50" rx="4" fill="#3B52FF" class="bar-grow delay-100" />
                                <rect x="17%" y="40" width="8%" height="80" rx="4" fill="#3B52FF" class="bar-grow delay-200" />
                                <rect x="31%" y="55" width="8%" height="65" rx="4" fill="#3B52FF" class="bar-grow delay-300" />
                                <rect x="45%" y="30" width="8%" height="90" rx="4" fill="#3B52FF" class="bar-grow delay-400" />
                                <rect x="59%" y="20" width="8%" height="100" rx="4" fill="#3B52FF" class="bar-grow delay-500" />
                                <rect x="73%" y="5" width="8%" height="115" rx="4" fill="#3B52FF" class="bar-grow delay-600" />
                                <rect x="87%" y="60" width="8%" height="60" rx="4" fill="#3B52FF" class="bar-grow delay-700" />
                            </svg>
                        </div>

                        <!-- X-Axis Labels -->
                        <div class="absolute bottom-0 left-8 right-2 flex justify-between text-[10px] text-slate-400 font-medium px-4">
                            <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CALL TO ACTION & FOOTER SECTION -->
    <section class="bg-brand-dark pt-10">
        <!-- Big Blue Box -->
        <div class="bg-brand-blue text-white py-20 text-center max-w-[1200px] mx-auto sm:rounded-[40px] px-6">
            <h2 class="text-3xl md:text-[42px] font-black tracking-tight mb-4">Mulai analisis penjualanmu sekarang</h2>
            <p class="text-blue-100 text-[15px] max-w-xl mx-auto mb-10 leading-relaxed">
                Bergabung dengan ratusan bisnis yang sudah menggunakan Salesight untuk mengelola dan menganalisis data penjualan mereka.
            </p>
            <a href="/register" class="bg-white text-brand-blue font-black px-8 py-3.5 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition transform inline-flex items-center gap-2">
                Get Started <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Footer Elements -->
        <div class="max-w-6xl mx-auto px-6 pt-24 pb-12 text-center text-sm">
            
            <div class="flex items-center justify-center gap-2 mb-8">
                <div class="w-8 h-8 bg-brand-blue rounded-lg flex items-center justify-center">
                    <i data-lucide="bar-chart-2" class="w-4 h-4 text-white"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tight">Salesight</span>
            </div>

            <div class="flex flex-wrap justify-center gap-x-8 gap-y-4 font-medium text-slate-400 mb-12">
                <a href="#" class="hover:text-white transition">Home</a>
                <a href="#fitur" class="hover:text-white transition">Features</a>
                <a href="#workflow" class="hover:text-white transition">How It Works</a>
                <a href="/login" class="hover:text-white transition">Login</a>
            </div>

            <div class="border-t border-slate-800 pt-8">
                <p class="text-slate-600 font-medium text-[13px]">&copy; 2025 PBL Kelompok 2. All rights reserved</p>
            </div>

        </div>
    </section>

    <!-- Scripts -->
    <script>
        // Inisialisasi icon Lucide
        lucide.createIcons();

        // Intersection Observer untuk memutar animasi hanya saat elemen di-scroll masuk ke layar
        document.addEventListener("DOMContentLoaded", function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.3 // Animasi berjalan saat 30% area elemen sudah terlihat di layar
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Tambahkan class play-anim untuk memutar CSS animation
                        entry.target.classList.add('play-anim');
                    } else {
                        // Hapus class ketika di-scroll lewat, agar animasi me-reset dan bisa diputar ulang
                        entry.target.classList.remove('play-anim');
                    }
                });
            }, observerOptions);

            // Cari semua elemen dengan class 'observe-anim' dan jalankan observer
            const animatedElements = document.querySelectorAll('.observe-anim');
            animatedElements.forEach((el) => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>