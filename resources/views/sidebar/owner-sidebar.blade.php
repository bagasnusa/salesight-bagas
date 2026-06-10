<aside class="sidebar-container">
    
    <a href="{{ route('owner.dashboard') }}" class="sidebar-brand-link">
        <div class="sidebar-header">
            <div class="brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="salesight-logo-svg">
                    <line x1="6" y1="20" x2="6" y2="16"></line>   <line x1="12" y1="20" x2="12" y2="4"></line>  <line x1="18" y1="20" x2="18" y2="10"></line>
                </svg>
            </div>
            <div class="brand-info">
                <h2 class="brand-name">Salesight</h2>
                <span class="brand-badge">Owner</span>
            </div>
        </div>
    </a>

    <nav class="sidebar-nav">
        <div class="nav-section-title">NAVIGASI</div>

        <a href="{{ route('owner.dashboard') }}" class="nav-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
            <img class="nav-icon" src="{{ asset('img/dashboard.png') }}" alt="Dashboard" />
            <span class="nav-text">Dashboard</span>
            @if (request()->routeIs('owner.dashboard')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ route('owner.tren-global') }}" class="nav-item {{ request()->routeIs('owner.tren-global') ? 'active' : '' }}">
            <img class="nav-icon" src="{{ asset('img/pglobal.png') }}" alt="Tren Global" />
            <span class="nav-text">Tren Penjualan Global</span>
            @if (request()->routeIs('owner.tren-global')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ route('owner.tren-toko') }}" class="nav-item {{ request()->routeIs('owner.tren-toko') ? 'active' : '' }}">
            <img class="nav-icon" src="{{ asset('img/ptoko.png') }}" alt="Tren Toko" />
            <span class="nav-text">Tren Penjualan Toko</span>
            @if (request()->routeIs('owner.tren-toko')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ route('owner.kontribusi-toko') }}" class="nav-item {{ request()->routeIs('owner.kontribusi-toko') ? 'active' : '' }}">
            <img class="nav-icon" src="{{ asset('img/kontribusitoko.png') }}" alt="Kontribusi" />
            <span class="nav-text">Kontribusi Toko</span>
            @if (request()->routeIs('owner.kontribusi-toko')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ route('owner.kelola-cabang') }}" class="nav-item {{ request()->routeIs('owner.kelola-cabang') ? 'active' : '' }}">
            <img class="nav-icon" src="{{ asset('img/kelolacabang.png') }}" alt="Kelola Cabang" />
            <span class="nav-text">Kelola Cabang</span>
            @if (request()->routeIs('owner.kelola-cabang')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ route('owner.daftar-toko') }}" class="nav-item {{ request()->routeIs('owner.daftar-toko') ? 'active' : '' }}">
            <img class="nav-icon" src="{{ asset('img/daftartoko.png') }}" alt="Daftar Toko" />
            <span class="nav-text">Daftar Toko</span>
            @if (request()->routeIs('owner.daftar-toko')) <div class="active-indicator"></div> @endif
        </a>
    </nav>

    <!-- Footer Sidebar -->
    <div class="sidebar-footer">
        <div class="owner-mode-card">
            <div class="mode-title">Owner Mode</div>
            <div class="mode-subtitle">Multi-branch analytics</div>
        </div>
        
        <!-- Ubah tag <button> menjadi <a> dan arahkan ke route login -->
        <a href="{{ route('login') }}" class="logout-btn">
            <img class="logout-icon" src="{{ asset('img/logout.png') }}" alt="Logout" />
            <span>Keluar</span>
        </a>
    </div>
</aside>