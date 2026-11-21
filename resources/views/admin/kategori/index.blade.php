
@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary-pk mb-1">Kelola Kategori</h3>
            <p class="text-muted mb-0">Tambah, ubah, dan kelola kategori produk.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali input:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h5 class="fw-bold mb-0">Tambah Kategori</h5>
        </div>

        <div class="card-body px-4 pb-4">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama_kategori"
                               class="form-control"
                               placeholder="Contoh: Makanan"
                               value="{{ old('nama_kategori') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <input type="text" name="deskripsi"
                               class="form-control"
                               placeholder="Deskripsi kategori"
                               value="{{ old('deskripsi') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h5 class="fw-bold mb-0">Daftar Kategori</h5>
        </div>

        <div class="card-body px-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No.</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $category->nama_kategori }}</td>
                                <td>{{ $category->deskripsi ?: '-' }}</td>
                                <td>
                                    @if($category->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editKategori{{ $category->id_kategori }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    <form action="{{ route('admin.kategori.destroy', $category->id_kategori) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade"
                                 id="editKategori{{ $category->id_kategori }}"
                                 tabindex="-1"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.kategori.update', $category->id_kategori) }}"
                                              method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                                <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Kategori</label>
                                                    <input type="text" name="nama_kategori"
                                                           class="form-control"
                                                           value="{{ $category->nama_kategori }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control"
                                                              rows="3">{{ $category->deskripsi }}</textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="aktif"
                                                            {{ $category->status === 'aktif' ? 'selected' : '' }}>
                                                            Aktif
                                                        </option>
                                                        <option value="nonaktif"
                                                            {{ $category->status === 'nonaktif' ? 'selected' : '' }}>
                                                            Nonaktif
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada kategori.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
