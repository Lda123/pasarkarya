@extends('layouts.app')

@section('title', 'Verifikasi Produk')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary-pk mb-0">Antrean Verifikasi Produk</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Gambar</th>
                        <th>Produk & Penjual</th>
                        <th>Kategori</th>
                        <th>Harga/Stok</th>
                        <th width="200" class="text-center">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td>
                            <img src="{{ $p->gambar ? asset('storage/products/' . $p->gambar) : 'https://placehold.co/80x80/eeeeee/999?text=No' }}" class="rounded shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold">{{ $p->nama_produk }}</div>
                            <small class="text-muted">Oleh: {{ $p->seller->nama }}</small>
                        </td>
                        <td>{{ $p->category->nama_kategori }}</td>
                        <td>
                            <div class="fw-bold text-accent">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                            <small>Stok: {{ $p->stok }}</small>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.produk.approve', $p->id_produk) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Setujui"><i class="bi bi-check-lg"></i> Setujui</button>
                            </form>
                            
                            {{-- Tombol Tolak memicu Modal --}}
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id_produk }}">
                                <i class="bi bi-x-lg"></i> Tolak
                            </button>

                            {{-- Modal Alasan Penolakan --}}
                            <div class="modal fade" id="rejectModal{{ $p->id_produk }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content text-start">
                                        <form action="{{ route('admin.produk.reject', $p->id_produk) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small text-muted">Berikan alasan mengapa produk <strong>{{ $p->nama_produk }}</strong> ditolak agar mahasiswa dapat memperbaikinya.</p>
                                                <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Contoh: Foto kurang jelas, deskripsi kurang lengkap..." required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle fs-1 text-success d-block mb-2"></i>
                            Tidak ada produk yang menunggu verifikasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection