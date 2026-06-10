@extends('layouts.owner')

@section('content')

<div class="kc-wrapper">
    
    @if(session('success'))
        <div style="background: #dcfce7; color: #16a34a; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="kc-header">
        <div class="kc-title-group">
            <h1 class="kc-title">Kelola Cabang</h1>
            <p class="kc-subtitle">Tambah, edit, dan pantau status semua cabang bisnis</p>
        </div>
        <button class="kc-btn-primary" onclick="toggleModal('modalTambahCabang')">
            <i data-lucide="plus"></i> Tambah Cabang
        </button>
    </div>

    <div class="kc-stats">
        <div class="kc-stat-card">
            <h2 class="kc-stat-value text-blue">{{ $branches->count() }}</h2>
            <p class="kc-stat-label">Total Cabang</p>
        </div>
        <div class="kc-stat-card">
            <h2 class="kc-stat-value text-green">{{ $branches->where('status', 'aktif')->count() }}</h2>
            <p class="kc-stat-label">Cabang Aktif</p>
        </div>
        <div class="kc-stat-card">
            <h2 class="kc-stat-value text-red">{{ $branches->where('status', 'nonaktif')->count() }}</h2>
            <p class="kc-stat-label">Cabang Nonaktif</p>
        </div>
    </div>

    <div class="kc-table-card">
        
        <div class="kc-table-header">
            <h3 class="kc-table-title">Daftar Cabang</h3>
            <span class="kc-table-count">{{ $branches->count() }} cabang terdaftar</span>
        </div>

        <div class="kc-table-responsive">
            <table class="kc-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">CABANG</th>
                        <th>LOKASI</th>
                        <th>KODE CABANG</th>
                        <th style="text-align: center;">STATUS</th>
                        <th style="text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse ($branches as $branch)
                    <tr>
                        <td>
                            <div class="kc-td-cabang">
                                @php
                                    $colors = ['blue', 'teal', 'orange', 'purple', 'red'];
                                    $randColor = $colors[$loop->index % count($colors)];
                                @endphp
                                <div class="kc-icon-box bg-{{ $randColor }}-light text-{{ $randColor }}"><i data-lucide="store"></i></div>
                                <span class="kc-cabang-name">{{ $branch->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="kc-td-lokasi">
                                <i data-lucide="map-pin"></i> {{ $branch->location }}
                            </div>
                        </td>
                        <td>
                            <div class="kc-td-kode">
                                <span class="kc-badge-kode">{{ $branch->branch_code }}</span>
                                <button class="kc-btn-salin" onclick="navigator.clipboard.writeText('{{ $branch->branch_code }}'); alert('Token berhasil disalin!');">
                                    <i data-lucide="copy"></i> Salin
                                </button>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            @if($branch->status == 'aktif')
                                <span class="kc-badge-status status-aktif"><span class="dot"></span> Aktif</span>
                            @else
                                <span class="kc-badge-status status-nonaktif"><span class="dot"></span> Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="kc-td-aksi">
                                <button class="kc-btn-edit" onclick="toggleModal('modalEditCabang-{{ $branch->branch_id }}')"><i data-lucide="edit-2"></i> Edit</button>
                                <button class="kc-btn-hapus" onclick="toggleModal('modalHapusCabang-{{ $branch->branch_id }}')"><i data-lucide="trash-2"></i> Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px 20px; color: #64748b;">
                            Belum ada data cabang yang terdaftar. Silakan klik tombol "Tambah Cabang".
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="modalTambahCabang" class="kc-modal-overlay">
    <div class="kc-modal-backdrop" onclick="toggleModal('modalTambahCabang')"></div>
    
    <div class="kc-modal-box">
        <button class="kc-modal-close" onclick="toggleModal('modalTambahCabang')">
            <i data-lucide="x"></i>
        </button>

        <div class="kc-modal-header">
            <h2>Tambah Cabang Baru</h2>
            <p>Daftarkan cabang baru ke sistem</p>
        </div>

        <form action="{{ route('owner.kelola-cabang.store') }}" method="POST" class="kc-modal-form">
            @csrf
            
            <div class="kc-form-group">
                <label>Nama Cabang <span class="text-red">*</span></label>
                <input type="text" name="name" placeholder="Contoh: Toko Semarang Tengah" required>
            </div>

            <div class="kc-form-group">
                <label>Lokasi <span class="text-red">*</span></label>
                <input type="text" name="location" placeholder="Contoh: Semarang, Jawa Tengah" required>
            </div>

            <div class="kc-auto-generate">
                <p class="kc-ag-title">Kode Cabang (Auto-Generate)</p>
                <div class="kc-ag-value">
                    <i data-lucide="hash"></i> SLS-???-00
                </div>
                <p class="kc-ag-desc">Kode dibuat otomatis berdasarkan nama dan lokasi cabang setelah disimpan.</p>
            </div>

            <div class="kc-modal-actions">
                <button type="button" class="kc-btn-cancel" onclick="toggleModal('modalTambahCabang')">Batal</button>
                <button type="submit" class="kc-btn-submit">Tambah Cabang</button>
            </div>
        </form>
    </div>
</div>

@foreach ($branches as $branch)

<div id="modalEditCabang-{{ $branch->branch_id }}" class="kc-modal-overlay">
    <div class="kc-modal-backdrop" onclick="toggleModal('modalEditCabang-{{ $branch->branch_id }}')"></div>
    
    <div class="kc-modal-box">
        <button class="kc-modal-close" onclick="toggleModal('modalEditCabang-{{ $branch->branch_id }}')">
            <i data-lucide="x"></i>
        </button>

        <div class="kc-modal-header">
            <h2>Edit Data Cabang</h2>
            <p>Perbarui informasi untuk cabang <strong>{{ $branch->branch_code }}</strong></p>
        </div>

        <form action="{{ route('owner.kelola-cabang.update', $branch->branch_id) }}" method="POST" class="kc-modal-form">
            @csrf
            @method('PUT') 

            <div class="kc-form-group">
                <label>Nama Cabang <span class="text-red">*</span></label>
                <input type="text" name="name" value="{{ $branch->name }}" required>
            </div>

            <div class="kc-form-group">
                <label>Lokasi <span class="text-red">*</span></label>
                <input type="text" name="location" value="{{ $branch->location }}" required>
            </div>

            <div class="kc-form-group">
                <label>Status Cabang</label>
                <select name="status" class="kc-select" required>
                    <option value="aktif" {{ $branch->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $branch->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="kc-modal-actions">
                <button type="button" class="kc-btn-cancel" onclick="toggleModal('modalEditCabang-{{ $branch->branch_id }}')">Batal</button>
                <button type="submit" class="kc-btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalHapusCabang-{{ $branch->branch_id }}" class="kc-modal-overlay">
    <div class="kc-modal-backdrop" onclick="toggleModal('modalHapusCabang-{{ $branch->branch_id }}')"></div>
    
    <div class="kc-modal-box text-center">
        <button class="kc-modal-close" onclick="toggleModal('modalHapusCabang-{{ $branch->branch_id }}')">
            <i data-lucide="x"></i>
        </button>

        <div class="kc-modal-icon-warning">
            <i data-lucide="alert-triangle"></i>
        </div>

        <div class="kc-modal-header">
            <h2>Hapus Cabang?</h2>
            <p>Apakah Anda yakin ingin menghapus cabang <strong>{{ $branch->name }}</strong>? Semua data yang terhubung akan ikut terhapus permanen.</p>
        </div>

        <form action="{{ route('owner.kelola-cabang.destroy', $branch->branch_id) }}" method="POST" class="kc-modal-form mt-4">
            @csrf
            @method('DELETE')
            <div class="kc-modal-actions">
                <button type="button" class="kc-btn-cancel" onclick="toggleModal('modalHapusCabang-{{ $branch->branch_id }}')">Batal</button>
                <button type="submit" class="kc-btn-delete-confirm">Ya, Hapus Cabang</button>
            </div>
        </form>
    </div>
</div>

@endforeach
<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if(modal) {
            modal.classList.toggle('active');
        }
    }
</script>

@endsection