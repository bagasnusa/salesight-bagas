<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
</head>

<body>

<div class="body">

    <div class="register-page">

        <div class="left-panel">

            <div class="container"></div>
            <div class="div"></div>
            <div class="container-2"></div>

            {{-- Decorative grid dots --}}
            <div class="grid-dots"></div>

            <div class="container-wrapper">

                <div class="container-3">

                    <div class="icon-wrapper">
                        <img
                        class="logo-image"
                        src="{{ asset('img/logo.png') }}"
                        alt="Logo">
                    </div>

                    <div class="text">
                        <div class="text-wrapper">Salesight</div>
                    </div>

                </div>

                <div class="container-4">

                    <div class="container-6">

                        <p class="bergabung-dengan">
                            <span class="span">Bergabung dengan<br></span>
                            <span class="text-wrapper-3">Salesight</span>
                        </p>

                        <p class="kelola-data">
                            Kelola data penjualan dan dapatkan insight bisnis dengan mudah bersama tim Anda.
                        </p>

                    </div>

                    <div class="container-7">

    <div class="container-8">

        <div class="container-9">

            <div class="div-wrapper">
                <div class="icon-3">
                    <i data-lucide="database"></i>
                </div>
            </div>

            <div class="container-10">

                <div class="container-11">
                    <div class="text-wrapper-4">
                        Admin (Data Entry)
                    </div>
                </div>

                <div class="container-12">
                    <p class="text-wrapper-5">
                        Input & kelola transaksi cabang
                    </p>
                </div>

            </div>

        </div>

        <div class="container-13">

            <div class="container-14">
                <p class="text-wrapper-6">
                    ✓ Input data transaksi penjualan harian
                </p>
            </div>

            <div class="container-14">
                <p class="text-wrapper-6">
                    ✓ Edit & kelola daftar transaksi cabang
                </p>
            </div>

            <div class="container-14">
                <p class="text-wrapper-6">
                    ✓ Akses form input produk & stok
                </p>
            </div>

        </div>

    </div>

    <div class="container-16">

        <div class="container-9">

            <div class="div-wrapper">
                <div class="icon-3">
                    <i data-lucide="layout-dashboard"></i>
                </div>
            </div>

            <div class="container-10">

                <div class="container-11">
                    <div class="text-wrapper-4">
                        Owner
                    </div>
                </div>

                <div class="container-12">
                    <p class="text-wrapper-5">
                        Pantau performa semua cabang
                    </p>
                </div>

            </div>

        </div>

        <div class="container-13">

            <div class="container-14">
                <p class="text-wrapper-6">
                    ✓ Dashboard analytics real-time
                </p>
            </div>

            <div class="container-14">
                <p class="text-wrapper-6">
                    ✓ Pantau performa semua cabang
                </p>
            </div>

            <div class="container-14">
                <p class="text-wrapper-6">
                    ✓ Laporan pendapatan otomatis
                </p>
            </div>

        </div>

    </div>

</div>

                </div>

            </div>

        </div>

        <div class="container-19">

            <div class="container-20">

                <div class="container-21">

                    <div class="container-22">

                        <div class="icon-5">
                            <i data-lucide="zap" class="badge-icon"></i>
                        </div>

                        <div class="text-wrapper-7">
                            Daftar Akun Baru
                        </div>

                    </div>

                    <div class="heading">
                        <div class="text-wrapper-8">
                            Buat Akun Salesight
                        </div>
                    </div>

                    <div class="paragraph">
                        <p class="isi-informasi-di">
                            Isi informasi di bawah dan pilih peran Anda untuk memulai.
                        </p>
                    </div>

                </div>

                <form id="register-form" class="form" method="POST" action="{{ route('register') }}">

                    @csrf

                    {{-- Error messages Global --}}
                    @if ($errors->any())
                        <div class="alert-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="container-23">

                        <div class="label">
                            <div class="text-wrapper-9">
                                Pilih Peran Anda
                            </div>
                        </div>

                        <div class="container-24">

                            <label class="button" id="label-owner">

                                <input type="radio" name="role" value="owner" hidden>

                                <div class="container-25 radio-dot"></div>

                                <div class="role-card-icon owner-icon">
                                    <i data-lucide="layout-dashboard"></i>
                                </div>

                                <div class="text-6">
                                    <div class="text-wrapper-10">
                                        Owner
                                    </div>
                                </div>

                                <div class="text-7">
                                    <div class="text-wrapper-11">
                                        Full Access
                                    </div>
                                </div>

                                <div class="paragraph-2">
                                    <p class="p">
                                        Kelola bisnis dan semua cabang
                                    </p>
                                </div>

                            </label>

                            <label class="button-2" id="label-admin">

                                <input type="radio" name="role" value="admin" hidden>

                                <div class="container-25 radio-dot"></div>

                                <div class="container-27">
                                    <div class="icon">
                                        <i data-lucide="database"></i>
                                    </div>
                                </div>

                                <div class="text-8">
                                    <div class="text-wrapper-12">
                                        Admin
                                    </div>
                                </div>

                                <div class="text-9">
                                    <div class="text-wrapper-13">
                                        Data Entry
                                    </div>
                                </div>

                                <div class="input-dan-kelola-wrapper">
                                    <p class="input-dan-kelola">
                                        Input dan kelola data penjualan untuk cabang tertentu
                                    </p>
                                </div>

                            </label>

                        </div>

                    </div>

                    <div class="container-28">

                        <div class="container-29"></div>

                        <div class="informasi-akun-wrapper">
                            <div class="informasi-akun">
                                INFORMASI AKUN
                            </div>
                        </div>

                        <div class="container-30"></div>

                    </div>

                    <div class="container-31">

                        <div class="label">
                            <div class="text-wrapper-9">
                                Nama Lengkap
                            </div>
                        </div>

                        <div class="container-32">

                            <div class="div-wrapper-2">

                                <input
                                    type="text"
                                    name="name"
                                    class="input-field"
                                    placeholder="Masukkan nama lengkap Anda"
                                    value="{{ old('name') }}"
                                    required
                                >

                            </div>

                        </div>

                    </div>

                    <div class="container-34">

                        <div class="label">
                            <div class="text-wrapper-9">
                                Email
                            </div>
                        </div>

                        <div class="container-32">

                            <div class="div-wrapper-2">

                                <input
                                    type="email"
                                    name="email"
                                    class="input-field"
                                    placeholder="nama@perusahaan.com"
                                    value="{{ old('email') }}"
                                    required
                                >

                            </div>

                        </div>

                    </div>

                    <div class="container-34">

                        <div class="label">
                            <div class="text-wrapper-9">
                                Password
                            </div>
                        </div>

                        <div class="container-32">

                            <div class="password-input">

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="input-field"
                                    placeholder="Minimal 8 karakter"
                                    required
                                >

                                <button type="button" class="toggle-password" onclick="togglePassword('password', this)" aria-label="Tampilkan password">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="container-34">

                        <div class="label">
                            <div class="text-wrapper-9">
                                Konfirmasi Password
                            </div>
                        </div>

                        <div class="container-32">

                            <div class="password-input">

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="input-field"
                                    placeholder="Ulangi password Anda"
                                    required
                                >

                                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)" aria-label="Tampilkan konfirmasi password">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="role-extra-card card-owner" id="card-owner" style="display:none;">

                        <div class="role-extra-header">
                            <div class="role-extra-icon owner-extra-icon">
                                <i data-lucide="store"></i>
                            </div>
                            <div class="role-extra-title-group">
                                <div class="role-extra-title">Owner — Informasi Toko</div>
                                <div class="role-extra-subtitle">Daftarkan bisnis Anda di Salesight</div>
                            </div>
                        </div>

                        <div class="role-extra-field">
                            <label class="role-extra-label">
                                Nama Toko / Bisnis <span class="required-star">*</span>
                            </label>
                            <div class="role-extra-input-wrap">
                                <i data-lucide="building-2" class="input-icon"></i>
                                <input
                                    type="text"
                                    name="store_name"
                                    id="store_name"
                                    class="input-field role-extra-input"
                                    placeholder="Contoh: Toko Sumber Makmur"
                                    value="{{ old('store_name') }}"
                                >
                            </div>
                            
                            @error('store_name')
                                <p style="color: #ef4444; font-size: 12px; margin-top: 8px; font-weight: 600;">
                                    {{ $message }}
                                </p>
                            @enderror

                            <p class="role-extra-hint">Nama ini akan tampil di dashboard dan laporan bisnis Anda.</p>
                        </div>

                    </div>

                    <div class="role-extra-card card-admin" id="card-admin" style="display:none;">

                        <div class="role-extra-header">
                            <div class="role-extra-icon admin-extra-icon">
                                <i data-lucide="git-branch"></i>
                            </div>
                            <div class="role-extra-title-group">
                                <div class="role-extra-title">Admin — Kode Akses Cabang</div>
                                <div class="role-extra-subtitle">Diperlukan untuk terhubung ke cabang</div>
                            </div>
                        </div>

                        <div class="role-extra-field">
                            <label class="role-extra-label">
                                Kode Cabang <span class="required-star">*</span>
                                <span class="info-tooltip" title="Kode cabang diberikan oleh owner bisnis Anda">
                                    <i data-lucide="info" class="info-icon"></i>
                                </span>
                            </label>
                            <div class="role-extra-input-wrap">
                                <i data-lucide="key-round" class="input-icon"></i>
                                <input
                                    type="text"
                                    name="branch_code"
                                    id="branch_code"
                                    class="input-field role-extra-input"
                                    placeholder="Masukkan kode cabang"
                                    value="{{ old('branch_code') }}"
                                >
                            </div>

                            @error('branch_code')
                                <p style="color: #ef4444; font-size: 12px; margin-top: 8px; font-weight: 600;">
                                    {{ $message }}
                                </p>
                            @enderror

                            <p class="role-extra-hint">Gunakan kode cabang yang diberikan oleh owner bisnis Anda.</p>
                        </div>

                    </div>

                    <button type="submit" class="button-4">

                        <div class="text-wrapper-15">
                            Daftar Sekarang
                        </div>

                        <div class="icon-8">
                            <i data-lucide="arrow-right" class="btn-arrow"></i>
                        </div>

                    </button>

                </form>

                <div class="sudah-punya-akun-wrapper">

                    <p class="sudah-punya-akun">

                        <span class="text-wrapper-17">
                            Sudah punya akun?
                        </span>

                        <a href="/login" class="text-wrapper-18">
                            Login di sini
                        </a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
// init lucide icons
lucide.createIcons();

function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('.eye-icon').style.opacity = isHidden ? '0.4' : '1';
}

// Role card selection + show/hide extra cards
document.querySelectorAll('input[name="role"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        // highlight role card
        document.querySelectorAll('.button, .button-2').forEach(function(label) {
            label.classList.remove('role-selected');
        });
        this.closest('label').classList.add('role-selected');

        // radio dot
        document.querySelectorAll('.radio-dot').forEach(d => d.classList.remove('radio-active'));
        this.closest('label').querySelector('.radio-dot').classList.add('radio-active');

        // show/hide extra cards
        const cardOwner = document.getElementById('card-owner');
        const cardAdmin = document.getElementById('card-admin');
        const storeInput = document.getElementById('store_name');
        const branchInput = document.getElementById('branch_code');

        if (this.value === 'owner') {
            cardOwner.style.display = 'block';
            cardAdmin.style.display = 'none';
            storeInput.setAttribute('required', 'required');
            branchInput.removeAttribute('required');
        } else if (this.value === 'admin') {
            cardAdmin.style.display = 'block';
            cardOwner.style.display = 'none';
            branchInput.setAttribute('required', 'required');
            storeInput.removeAttribute('required');
        }

        // re-init lucide for newly visible icons
        lucide.createIcons();
    });
});

// restore state on old() after validation error
(function() {
    const oldRole = '{{ old("role") }}';
    if (oldRole) {
        const radio = document.querySelector('input[name="role"][value="' + oldRole + '"]');
        if (radio) {
            radio.dispatchEvent(new Event('change'));
        }
    }
})();

// VALIDASI ROLE SEBELUM SUBMIT
document.getElementById('register-form').addEventListener('submit', function(e) {
    const roleSelected = document.querySelector('input[name="role"]:checked');
    if (!roleSelected) {
        e.preventDefault(); // Mencegah form dikirim
        alert('Silakan Pilih Peran Anda (Owner / Admin) terlebih dahulu sebelum mendaftar.');
        
        // Opsional: Berikan efek scroll/highlight ke area pilihan role
        document.querySelector('.container-23').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>

</body>
</html>