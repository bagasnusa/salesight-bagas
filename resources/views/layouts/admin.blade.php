<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salesight - Admin Panel</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Icon Library: Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">

<div class="flex min-h-screen">
a
    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r">

        <div class="p-6 border-b">
            <h1 class="text-xl font-bold">
                Salesight
            </h1>
        </div>

        <div class="p-4">

            <p class="text-xs text-gray-400 mb-3">
                MENU UTAMA
            </p>

            <a href="{{ route('admin.dashboard') }}"
               class="block bg-indigo-600 text-white px-4 py-3 rounded-xl mb-2">
                Dashboard
            </a>

            <a href="{{ route('admin.data-transaksi') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 mb-2">
                Data Transaksi
            </a>

            <a href="{{ route('admin.input-data') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 mb-2">
                Input Data
            </a>

            <a href="{{ route('admin.laporan') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100">
                Laporan
            </a>

<body class="bg-[#f5f7fb] text-gray-800 text-sm">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-36 bg-white border-r border-gray-100 flex flex-col">

        <!-- Menu -->
        <div class="flex-1 p-3">

            <p class="text-[10px] text-gray-400 uppercase mb-4 tracking-wider">
                MENU UTAMA
            </p>

            <ul class="space-y-1">

                <li>
                    <a href="/admin/dashboard"
                       class="block px-3 py-2 rounded-lg transition text-xs
                       {{ request()->is('admin/dashboard')
                           ? 'bg-indigo-600 text-white'
                           : 'text-gray-600 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="/admin/data-transaksi"
                       class="block px-3 py-2 rounded-lg transition text-xs
                       {{ request()->is('admin/data-transaksi')
                           ? 'bg-indigo-600 text-white'
                           : 'text-gray-600 hover:bg-gray-100' }}">
                        Data Transaksi
                    </a>
                </li>

                <li>
                    <a href="/admin/input-data"
                       class="block px-3 py-2 rounded-lg transition text-xs
                       {{ request()->is('admin/input-data')
                           ? 'bg-indigo-600 text-white'
                           : 'text-gray-600 hover:bg-gray-100' }}">
                        Input Data
                    </a>
                </li>

                <li>
                    <a href="/admin/laporan"
                       class="block px-3 py-2 rounded-lg transition text-xs
                       {{ request()->is('admin/laporan')
                           ? 'bg-indigo-600 text-white'
                           : 'text-gray-600 hover:bg-gray-100' }}">
                        Laporan
                    </a>
                </li>

            </ul>

        </div>

        <!-- Bottom -->
        <div class="p-3">

            <div class="bg-indigo-50 rounded-xl p-3">

                <h3 class="text-indigo-600 font-semibold text-xs">
                    Admin Mode
                </h3>

                <p class="text-gray-400 text-[10px] mt-1">
                    Data entry & kelola transaksi
                </p>

            </div>
>>>>>>> b6da257 (Save progress sebelum sync)

        </div>
        
    </aside>


    <!-- CONTENT -->
    <div class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <header class="bg-white border-b h-20 flex justify-between items-center px-8">

            <div>
                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg text-sm">
                    Admin Panel
                </span>
            </div>

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-full bg-indigo-600"></div>

                <div>
                    <p class="font-semibold">
                        Admin Fulan
                    </p>

                    <small class="text-gray-500">
                        Data Entry
                    </small>
=======
    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Navbar -->
        <header class="bg-white h-14 px-4 flex items-center justify-between border-b border-gray-100">

            <!-- Left -->
            <div class="flex items-center gap-3">

                <!-- Menu -->
                <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>

                    </svg>

                </button>

                <!-- Logo -->
                <div class="flex items-center gap-2">

                    <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-3 h-3 text-white"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 12v7m7-14v14m7-10v10"/>

                        </svg>

                    </div>

                    <h1 class="font-bold text-sm">
                        Salesight
                    </h1>

                    <span class="bg-orange-100 text-orange-500 text-[10px] px-2 py-1 rounded-md">
                        Admin Panel
                    </span>

>>>>>>> b6da257 (Save progress sebelum sync)
                </div>

            </div>


        </header>

        <!-- PAGE CONTENT -->
        <main class="p-8">
=======
            <!-- Right -->
            <div class="flex items-center gap-3">

                <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                    A
                </div>

                <div class="leading-tight">
                    <h3 class="font-semibold text-[11px]">
                        Admin Fulan
                    </h3>

                    <p class="text-[10px] text-gray-400">
                        Data Entry
                    </p>
                </div>

                <button class="border border-gray-200 px-3 py-1.5 rounded-lg text-[11px] hover:bg-red-50 hover:text-red-500 transition">
                    Keluar
                </button>

            </div>

        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-4">
>>>>>>> b6da257 (Save progress sebelum sync)
            @yield('content')
        </main>

    <!-- Initialize Icons -->
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>