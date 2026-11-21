@extends('layouts.app')

@section('title', 'Tentang — PasarKarya')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-3">Tentang PasarKarya</h2>
            <p class="text-muted">PasarKarya adalah website marketplace sederhana yang menjadi wadah bagi mahasiswa untuk memperkenalkan dan menjual produk fisik hasil karya mereka yang sudah selesai dinilai, seperti kerajinan, alat sederhana, prototipe, media pembelajaran, dan produk kreatif lainnya yang masih layak digunakan.</p>
            <p class="text-muted">Melalui PasarKarya, calon pembeli dapat menemukan produk karya mahasiswa dengan mudah, sementara mahasiswa dapat mengelola produk dan pesanan mereka secara terpusat.</p>
            <a href="{{ route('home') }}" class="btn btn-pk mt-3"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection