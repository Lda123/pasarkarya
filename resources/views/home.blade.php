@extends('layouts.app')

@section('title', 'PasarKarya - Hasil Karya Mahasiswa')

@section('content')
{{-- Hero --}}
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-white text-dark border mb-3 px-3 py-2 shadow-sm">
                    Selamat Datang di PasarKarya
                </span>
                <h1>Produk <span class="highlight">Berkualitas.</span><br>Dari Karya Mahasiswa.</h1>
                <p class="text-muted mt-3">
                    Temukan berbagai produk kreatif, alat sederhana, dan prototipe hasil karya mahasiswa yang telah dinilai dan siap digunakan.
                </p>
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('catalog') }}" class="btn btn-pk px-4 py-2">
                        Lihat Katalog <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-accent px-4 py-2">
                        Daftar Sekarang
                    </a>
                </div>
            </div>

            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=800&auto=format&fit=crop"
                    class="img-fluid rounded-4 shadow-sm" alt="Hero Image">
            </div>
        </div>
    </div>
</section>

{{-- Kategori (Langsung diarahkan ke Katalog) --}}
<section class="py-5">
    <div class="container">
        <h4 class="text-center fw-bold mb-1 text-primary-pk">
            Belanja Berdasarkan Kategori
        </h4>
        <p class="text-center text-muted mb-4">
            Pilih kategori produk yang Anda butuhkan
        </p>

        <div class="row justify-content-center g-3">
            @foreach ($categories as $category)
                @php
                    $slug = Str::slug($category->nama_kategori);
                @endphp

                <div class="col-4 col-md-2 text-center">
                    {{-- Klik ikon langsung membawa ke halaman katalog dengan filter kategori --}}
                    <a href="{{ route('catalog', ['category' => $category->id_kategori]) }}" class="text-decoration-none text-dark">
                        <div class="category-circle">
                            <img src="{{ asset('images/categories/' . $slug . '.png') }}" alt="{{ $category->nama_kategori }}" onerror="this.onerror=null; this.src='https://placehold.co/120x120/f1f1f1/0f1b38?text=Kategori'">
                        </div>
                        <small class="fw-semibold text-muted">
                            {{ $category->nama_kategori }}
                        </small>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Preview Produk Terbaru (Hanya Menampilkan 8 Produk Tanpa Filter) --}}
<section class="py-4" id="daftar-produk">
    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-primary-pk">Produk Terbaru</h4>
                <small class="text-muted">Temukan produk hasil karya mahasiswa.</small>
            </div>
            <a href="{{ route('catalog') }}" class="text-decoration-none fw-bold text-accent">
                Lihat Semua Produk <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        {{-- Keterangan: Form Filter Lokasi sudah dihapus dari sini agar Home tetap bersih --}}

        <div class="row g-3">
            @forelse ($products as $product)
                <div class="col-6 col-md-3">
                    <div class="product-card h-100 d-flex flex-column">
                        <div class="position-relative">
                            <img src="{{ Str::startsWith($product->gambar, 'http') ? $product->gambar : ($product->gambar ? asset('storage/products/' . $product->gambar) : 'https://placehold.co/400x300/eeeeee/999?text=' . urlencode($product->nama_produk)) }}" alt="{{ $product->nama_produk }}">
                            <span class="badge badge-pk position-absolute top-0 start-0 m-2">TERBARU</span>
                        </div>
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <small class="text-muted">{{ $product->category->nama_kategori ?? 'Tanpa Kategori' }}</small>
                            <h6 class="fw-bold mt-1 mb-1 text-truncate" title="{{ $product->nama_produk }}">{{ $product->nama_produk }}</h6>
                            
                            @php
                                $penjual = $product->seller;
                                $lokasiPenjual = $penjual ? collect([$penjual->kota_kabupaten, $penjual->kecamatan])->filter()->implode(', ') : '';
                            @endphp
                            <div class="small text-muted mb-2 text-truncate">
                                <i class="bi bi-geo-alt me-1"></i> {{ $lokasiPenjual ?: 'Lokasi tidak diketahui' }}
                            </div>

                            <div class="mt-auto pt-2">
                                <p class="fw-bold mb-1 text-accent">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                                <small class="text-muted d-block mb-2">Stok: {{ $product->stok }}</small>
                                <a href="{{ route('produk.show', $product->id_produk) }}" class="btn btn-pk btn-sm w-100">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    Belum ada produk untuk ditampilkan.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Promo Banner --}}
<section class="py-4">
    <div class="container">
        <div class="promo-banner p-4 p-md-5 d-flex flex-column flex-md-row justify-content-between align-items-center shadow-sm">
            <div>
                <h3 class="fw-bold mb-1">Dukung Karya Mahasiswa!</h3>
                <p class="mb-0 opacity-75">Dapatkan produk berkualitas dengan harga terjangkau.</p>
            </div>
            <a href="{{ route('catalog') }}" class="btn btn-accent px-4 py-2 mt-3 mt-md-0">
                <i class="bi bi-bag me-1"></i> Mulai Belanja
            </a>
        </div>
    </div>
</section>

{{-- Keunggulan --}}
<section class="py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <i class="bi bi-patch-check fs-1 text-primary-pk"></i>
                <h6 class="fw-bold mt-2">Produk Terverifikasi</h6>
                <p class="text-muted small mb-0">Setiap produk telah diperiksa oleh admin.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-cash-coin fs-1 text-primary-pk"></i>
                <h6 class="fw-bold mt-2">Harga Terjangkau</h6>
                <p class="text-muted small mb-0">Langsung dari mahasiswa tanpa perantara.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-box-seam fs-1 text-primary-pk"></i>
                <h6 class="fw-bold mt-2">Ambil Langsung</h6>
                <p class="text-muted small mb-0">Tanpa biaya kirim, ambil sesuai kesepakatan.</p>
            </div>
        </div>
    </div>
</section>
@endsection