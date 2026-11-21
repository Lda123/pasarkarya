@extends('layouts.app')

@section('title', $product->nama_produk . ' — PasarKarya')

@section('content')

<div class="container py-3 py-lg-4 product-detail">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted">Beranda</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted">Katalog</a>
            </li>
            <li class="breadcrumb-item active">
                {{ $product->nama_produk }}
            </li>
        </ol>
    </nav>

    <div class="row g-3 align-items-stretch product-detail-row">

        {{-- GAMBAR KIRI --}}
        <div class="col-lg-5">
            <div class="product-image-sticky">
                <div class="product-image-frame">
                    <img
                        src="{{ Str::startsWith($product->gambar, 'http')
                            ? $product->gambar
                            : ($product->gambar
                                ? asset('storage/products/' . $product->gambar)
                                : 'https://placehold.co/600x600/eeeeee/999?text=' . urlencode($product->nama_produk)) }}"
                        alt="{{ $product->nama_produk }}"
                        class="product-main-image"
                    >
                </div>
            </div>
        </div>

        {{-- INFORMASI KANAN --}}
        <div class="col-lg-7">
            <div class="product-info-card">

                {{-- Kategori dan Status --}}
                <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-2 rounded-pill">
                        {{ $product->category->nama_kategori }}
                    </span>

                    <span class="badge {{ $product->stok > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                        {{ $product->stok > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                </div>

                {{-- Nama Produk --}}
                <h3 class="fw-bold text-primary-pk product-title">
                    {{ $product->nama_produk }}
                </h3>

                {{-- Penjual --}}
                <div class="product-seller mb-3 pb-3 border-bottom">
                    <i class="bi bi-shop text-muted"></i>
                    <span class="text-muted">
                        Dijual oleh:
                        <strong class="text-dark">{{ $product->seller->nama }}</strong>
                    </span>
                </div>

                {{-- Harga --}}
                <div class="product-price-section mb-3">
                    <span class="text-muted small d-block mb-1">
                        Harga Produk
                    </span>

                    <h4 class="fw-bold text-accent mb-0 product-price">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </h4>
                </div>

                {{-- Deskripsi --}}
                <div class="product-description-section">
                    <h6 class="fw-bold mb-2">
                        Deskripsi Produk
                    </h6>

                    @php
                        $deskripsi = trim($product->deskripsi ?? 'Tidak ada deskripsi.');
                        $isLong = mb_strlen($deskripsi) > 250;
                        $shortDeskripsi = $isLong ? Str::limit($deskripsi, 250) : $deskripsi;
                    @endphp

                    @if($isLong)
                        <div id="shortDescription">
                            <div class="text-muted product-description">
                                {!! nl2br(e($shortDeskripsi)) !!}
                            </div>

                            <button class="btn btn-link p-0 text-decoration-none fw-bold text-accent mt-2 product-read-more"
                                    type="button" onclick="toggleDescription()">
                                Baca Selengkapnya <i class="bi bi-chevron-down ms-1"></i>
                            </button>
                        </div>

                        <div id="fullDescription" style="display: none;">
                            <div class="text-muted product-description">
                                {!! nl2br(e($deskripsi)) !!}
                            </div>

                            <button class="btn btn-link p-0 text-decoration-none fw-bold text-accent mt-2 product-read-more"
                                    type="button" onclick="toggleDescription()">
                                Tutup <i class="bi bi-chevron-up ms-1"></i>
                            </button>
                        </div>
                    @else
                        <div class="text-muted product-description">
                            {!! nl2br(e($deskripsi)) !!}
                        </div>
                    @endif
                </div>

                {{-- Stok dan Tombol Keranjang --}}
                <div class="product-order-section">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted small">
                            <i class="bi bi-box-seam me-1"></i> Sisa Stok
                        </span>

                        <span class="fw-bold">
                            {{ $product->stok }} unit
                        </span>
                    </div>

                    @if($product->stok > 0)

                        @auth
                            @if(auth()->user()->role === 'pembeli')

                                <form action="{{ route('pembeli.keranjang.store', $product->id_produk) }}"
                                      method="POST">
                                    @csrf

                                    <button type="submit"
                                            class="btn btn-pk w-100 py-3 product-order-btn">
                                        <i class="bi bi-cart-plus me-2"></i>
                                        Tambahkan ke Keranjang
                                    </button>
                                </form>

                                <a href="{{ route('pembeli.keranjang.index') }}"
                                   class="btn btn-outline-primary w-100 py-2 mt-2">
                                    <i class="bi bi-cart me-2"></i>
                                    Lihat Keranjang
                                </a>

                            @else

                                <button class="btn btn-secondary w-100 py-3" disabled>
                                    Hanya Pembeli yang bisa memesan
                                </button>

                            @endif

                        @else

                            <a href="{{ route('login') }}"
                               class="btn btn-pk w-100 py-3 product-order-btn">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login untuk Menambahkan ke Keranjang
                            </a>

                        @endauth

                    @else

                        <button class="btn btn-secondary w-100 py-3" disabled>
                            <i class="bi bi-cart-x me-2"></i>
                            Stok Habis
                        </button>

                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

{{-- Script Toggle Deskripsi --}}
<script>
    function toggleDescription() {
        const shortDesc = document.getElementById('shortDescription');
        const fullDesc = document.getElementById('fullDescription');

        if (shortDesc.style.display === 'none') {
            shortDesc.style.display = 'block';
            fullDesc.style.display = 'none';
        } else {
            shortDesc.style.display = 'none';
            fullDesc.style.display = 'block';
        }
    }
</script>

@endsection
