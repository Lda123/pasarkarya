@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold text-primary-pk mb-4">Dashboard Admin</h3>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4">
                <p class="text-muted mb-1 fw-bold small">TOTAL PENGGUNA</p>
                <h2 class="fw-bold text-primary-pk mb-0">{{ $totalPengguna }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4">
                <p class="text-muted mb-1 fw-bold small">TOTAL PRODUK</p>
                <h2 class="fw-bold text-primary-pk mb-0">{{ $totalProduk }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4">
                <p class="text-muted mb-1 fw-bold small">MENUNGGU VERIFIKASI</p>
                <h2 class="fw-bold text-warning mb-0">{{ $produkMenunggu }}</h2>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">Verifikasi Produk Mahasiswa</h5>
                    <p class="text-muted">
                        Periksa produk baru dari mahasiswa dan tentukan apakah produk tersebut layak tampil di katalog.
                    </p>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-pk px-4">
                        Lihat Antrean Verifikasi
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">Kelola Kategori Produk</h5>
                    <p class="text-muted">
                        Tambahkan kategori baru, ubah informasi kategori, dan atur status kategori produk.
                    </p>
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-pk px-4">
                        Kelola Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
