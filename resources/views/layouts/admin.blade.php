<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salesight Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f5f7fb]">

<div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r">

        <div class="p-6 border-b">
            <h1 class="text-xl font-bold">
                Salesight
            </h1>
        </div>

        <div class="p-4">

            <p class="text-xs text-gray-400 mb-4">
                MENU UTAMA
            </p>

            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-3 rounded-xl mb-2
               {{ request()->routeIs('admin.dashboard')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-gray-100' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.data-transaksi') }}"
               class="block px-4 py-3 rounded-xl mb-2
               {{ request()->routeIs('admin.data-transaksi')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-gray-100' }}">
                Data Transaksi
            </a>

            <a href="{{ route('admin.input-data') }}"
               class="block px-4 py-3 rounded-xl mb-2
               {{ request()->routeIs('admin.input-data')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-gray-100' }}">
                Input Data
            </a>

            <a href="{{ route('admin.laporan') }}"
               class="block px-4 py-3 rounded-xl
               {{ request()->routeIs('admin.laporan')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-gray-100' }}">
                Laporan
            </a>

        </div>

        <div class="mt-auto p-4">
            <div class="bg-indigo-50 rounded-xl p-4">
                <h3 class="font-semibold text-indigo-600">
                    Admin Mode
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Data entry & kelola transaksi
                </p>
            </div>
        </div>

    </aside>

    <!-- Content -->
    <div class="flex-1 flex flex-col">

        <!-- Topbar -->
        <header class="bg-white border-b px-8 py-4 flex justify-between items-center">

            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg">
                Admin Panel
            </span>

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-full bg-indigo-600"></div>

                <div>
                    <p class="font-semibold">
                        Admin Fulan
                    </p>

                    <small class="text-gray-500">
                        Data Entry
                    </small>
                </div>

            </div>

        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-8">

            @yield('content')

        </main>

    </div>

</div>

@yield('scripts')

</body>
</html>