@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary-pk mb-1">Dashboard Mahasiswa</h3>
            <p class="text-muted">Selamat datang, {{ auth()->user()->nama }}. Kelola produk Anda di sini.</p>
        </div>
        <a href="{{ route('mahasiswa.produk.create') }}" class="btn btn-accent px-4 py-2">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold small">TOTAL PRODUK</p>
                        <h2 class="fw-bold text-primary-pk mb-0">{{ $totalProduk }}</h2>
                    </div>
                    <i class="bi bi-box-seam fs-1 text-primary-pk opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold small">MENUNGGU VERIFIKASI</p>
                        <h2 class="fw-bold text-warning mb-0">{{ $produkMenunggu }}</h2>
                    </div>
                    <i class="bi bi-hourglass-split fs-1 text-warning opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold small">PRODUK DISETUJUI</p>
                        <h2 class="fw-bold text-success mb-0">{{ $produkDisetujui }}</h2>
                    </div>
                    <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 text-center">
            <h5 class="fw-bold mb-3">Kelola Produk Anda</h5>
            <p class="text-muted">Lihat daftar produk, perbarui stok, atau tambahkan karya baru Anda.</p>
            <a href="{{ route('mahasiswa.produk.index') }}" class="btn btn-pk px-4">Lihat Daftar Produk</a>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('mahasiswa.pesanan.index') }}" class="btn btn-outline-primary px-4 py-2">
            <i class="bi bi-cart-check"></i> Lihat Pesanan Masuk ({{ $pesananMasuk }})
        </a>
    </div>
</div>
@endsection