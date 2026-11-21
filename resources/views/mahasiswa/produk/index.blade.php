@extends('layouts.app')

@section('title', 'Produk Saya')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary-pk mb-0">Daftar Produk Saya</h3>
        <div class="d-flex gap-2">
            {{-- Tombol Hapus Massal (Muncul jika ada yang dicentang) --}}
            <button type="button" id="btnBulkDelete" class="btn btn-danger d-none">
                <i class="bi bi-trash me-1"></i> Hapus Terpilih (<span id="countSelected">0</span>)
            </button>
            <a href="{{ route('mahasiswa.produk.create') }}" class="btn btn-accent">
                <i class="bi bi-plus-lg"></i> Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form tersembunyi untuk Hapus Massal --}}
    <form id="bulkDeleteForm" action="{{ route('mahasiswa.produk.bulkDestroy') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
        <div id="bulkIdsContainer"></div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        {{-- Checkbox Pilih Semua --}}
                        <th width="40" class="text-center">
                            <input class="form-check-input" type="checkbox" id="selectAll" style="cursor: pointer;">
                        </th>
                        <th width="80" class="text-center">Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        {{-- Checkbox per produk --}}
                        <td class="text-center">
                            <input class="form-check-input product-checkbox" type="checkbox" value="{{ $p->id_produk }}" style="cursor: pointer;">
                        </td>
                        <td class="text-center">
                            <img src="{{ Str::startsWith($p->gambar, 'http') ? $p->gambar : ($p->gambar ? asset('storage/products/' . $p->gambar) : 'https://placehold.co/400x300/eeeeee/999?text=' . urlencode($p->nama_produk)) }}" alt="{{ $p->nama_produk }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold">{{ $p->nama_produk }}</div>
                            @if($p->status_verifikasi == 'ditolak' && $p->catatan_admin)
                                <small class="text-danger"><i class="bi bi-info-circle"></i> {{ $p->catatan_admin }}</small>
                            @endif
                        </td>
                        <td>{{ $p->category->nama_kategori }}</td>
                        <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td>{{ $p->stok }}</td>
                        <td>
                            @if($p->status_verifikasi == 'menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($p->status_verifikasi == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($p->status_verifikasi == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('mahasiswa.produk.edit', $p->id_produk) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            
                            @if($p->status_verifikasi != 'nonaktif')
                                {{-- Tombol Hapus (Memicu Modal) --}}
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-single" 
                                    data-id="{{ $p->id_produk }}" 
                                    data-name="{{ $p->nama_produk }}" 
                                    title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                                
                                {{-- Form tersembunyi untuk Hapus Satuan --}}
                                <form id="delete-form-{{ $p->id_produk }}" action="{{ route('mahasiswa.produk.destroy', $p->id_produk) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada produk. Silakan tambah produk baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS (GLOBAL) --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: var(--pk-primary);">
                <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <h5 class="mt-3 fw-bold">Apakah Anda yakin?</h5>
                <p class="text-muted" id="deleteMessage">Produk akan dihapus secara permanen dari sistem.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger px-4" id="btnConfirmDelete">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModalEl = document.getElementById('deleteModal');
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        const btnConfirmDelete = document.getElementById('btnConfirmDelete');
        let formToSubmit = null; // Variabel untuk menyimpan form mana yang akan disubmit

        // --- 1. LOGIKA TOMBOL HAPUS SATUAN ---
        document.querySelectorAll('.btn-delete-single').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                // PERBAIKAN: Menggunakan 'name' untuk hapus satuan
                document.getElementById('deleteMessage').innerHTML = `Produk <strong>"${name}"</strong> akan dihapus secara permanen dari sistem.`;
                
                // Simpan form yang akan disubmit ke dalam variabel
                formToSubmit = document.getElementById('delete-form-' + id);
                
                deleteModal.show();
            });
        });

        // --- 2. LOGIKA CHECKBOX PILIH SEMUA & HAPUS MASSAL ---
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const countSelected = document.getElementById('countSelected');

        function updateBulkButton() {
            const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
            
            if (checkedCount > 0) {
                btnBulkDelete.classList.remove('d-none');
                countSelected.textContent = checkedCount;
            } else {
                btnBulkDelete.classList.add('d-none');
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.product-checkbox:checked').length === checkboxes.length;
                if (selectAll) selectAll.checked = allChecked;
                updateBulkButton();
            });
        });

        // Tombol Hapus Massal diklik
        btnBulkDelete.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
            if (checkedBoxes.length === 0) return;

            // PERBAIKAN: Menggunakan 'checkedBoxes.length' untuk hapus massal
            document.getElementById('deleteMessage').innerHTML = `Anda akan menghapus secara permanen <strong>${checkedBoxes.length} produk</strong> sekaligus.`;
            
            // Siapkan form bulk
            const bulkForm = document.getElementById('bulkDeleteForm');
            const container = document.getElementById('bulkIdsContainer');
            container.innerHTML = ''; // Bersihkan input sebelumnya

            checkedBoxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });

            // Simpan form bulk ke variabel
            formToSubmit = bulkForm;
            
            deleteModal.show();
        });

        // --- 3. LOGIKA TOMBOL KONFIRMASI DI DALAM MODAL ---
        btnConfirmDelete.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit(); // Submit form yang sudah disiapkan
            }
        });
    });
</script>
@endpush