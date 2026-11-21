@extends('layouts.app')

@section('title', 'Katalog Produk — PasarKarya')

@section('content')
<div class="bg-white border-bottom py-4">
    <div class="container">
        <h2 class="fw-bold text-primary-pk mb-1">Katalog Produk</h2>
        <p class="text-muted mb-0">Temukan seluruh karya terbaik dari mahasiswa.</p>
    </div>
</div>

<div class="container py-4">
    {{-- SEARCH BAR & TOMBOL FILTER --}}
    <div class="row g-2 mb-4">
        <div class="col-md-9">
            <form action="{{ route('catalog') }}" method="GET">
                {{-- Pertahankan filter jika sedang aktif --}}
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('categories') && is_array(request('categories'))) 
                    @foreach(request('categories') as $catId) <input type="hidden" name="categories[]" value="{{ $catId }}"> @endforeach 
                @endif
                @if(request('lokasi')) <input type="hidden" name="lokasi" value="{{ request('lokasi') }}"> @endif
                @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama produk impianmu di sini..." value="{{ request('search') }}">
                    <button class="btn btn-pk px-4" type="submit">Cari</button>
                </div>
            </form>
        </div>
        <div class="col-md-3">
            <button class="btn btn-outline-secondary btn-lg w-100 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel" aria-expanded="false" aria-controls="filterPanel">
                <i class="bi bi-funnel me-1"></i> Filter Pencarian
            </button>
        </div>
    </div>

    {{-- PANEL FILTER (CHECKBOX & HARGA) --}}
    <div class="collapse mb-4" id="filterPanel">
        <div class="card card-body border-0 shadow-sm bg-light">
            <form action="{{ route('catalog') }}" method="GET" class="row g-4">
                {{-- Pertahankan kata kunci pencarian --}}
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                {{-- Filter Kategori (Checkbox) --}}
                <div class="col-12">
                    <label class="form-label fw-bold text-primary-pk mb-2">Kategori Produk</label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($categories as $cat)
                            @php
                                $isChecked = (is_array(request('categories')) && in_array($cat->id_kategori, request('categories'))) || request('category') == $cat->id_kategori;
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id_kategori }}" id="cat_{{ $cat->id_kategori }}" {{ $isChecked ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_{{ $cat->id_kategori }}">
                                    {{ $cat->nama_kategori }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="my-2">

                <div class="col-md-6">
                    {{-- Filter Rentang Harga --}}
                    <label class="form-label fw-bold text-primary-pk mb-2">Rentang Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="min_price" class="form-control" placeholder="Harga Min" value="{{ request('min_price') }}" min="0">
                        <span class="input-group-text">-</span>
                        <input type="number" name="max_price" class="form-control" placeholder="Harga Max" value="{{ request('max_price') }}" min="0">
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- Filter Lokasi --}}
                    <label class="form-label fw-bold text-primary-pk mb-2">Lokasi Penjual</label>
                    @if(auth()->check() && auth()->user()->role === 'pembeli')
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="lokasi" value="terdekat" id="lokasi_terdekat" {{ request('lokasi') == 'terdekat' ? 'checked' : '' }}>
                            <label class="form-check-label" for="lokasi_terdekat">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Hanya tampilkan dari lokasi terdekat saya
                            </label>
                        </div>
                    @else
                        <div class="alert alert-secondary py-2 small mb-0">Login sebagai pembeli untuk filter lokasi.</div>
                    @endif
                </div>

                {{-- Tombol Aksi --}}
                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('catalog') }}" class="btn btn-outline-danger px-4">Reset Filter</a>
                    <button type="submit" class="btn btn-pk px-4">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Pencarian Aktif --}}
    @if(request('search') || request('category') || request('categories') || request('lokasi') || request('min_price') || request('max_price'))
        <div class="alert alert-info py-2 small d-flex flex-wrap align-items-center gap-2 mb-4">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <span>Menampilkan hasil untuk:</span>
            
            {{-- 1. Tampilkan Keyword Pencarian --}}
            @if(request('search')) 
                <span class="badge bg-primary">Keyword: "{{ request('search') }}"</span> 
            @endif

            {{-- 2. Tampilkan Kategori Tunggal (Dari klik Ikon di Home) --}}
            @if(request('category'))
                @php
                    $activeCat = $categories->where('id_kategori', request('category'))->first();
                @endphp
                @if($activeCat)
                    <span class="badge bg-primary">Kategori: "{{ $activeCat->nama_kategori }}"</span>
                @endif
            @endif

            {{-- 3. Tampilkan Banyak Kategori (Dari Checkbox Filter) --}}
            @if(request('categories') && is_array(request('categories')))
                @foreach(request('categories') as $catId)
                    @php
                        $cat = $categories->where('id_kategori', $catId)->first();
                    @endphp
                    @if($cat)
                        <span class="badge bg-primary">Kategori: "{{ $cat->nama_kategori }}"</span>
                    @endif
                @endforeach
            @endif

            {{-- 4. Tampilkan Rentang Harga --}}
            @if(request('min_price') || request('max_price')) 
                <span class="badge bg-primary">
                    Harga: Rp {{ request('min_price') ? number_format(request('min_price'), 0, ',', '.') : '0' }} 
                    - Rp {{ request('max_price') ? number_format(request('max_price'), 0, ',', '.') : '∞' }}
                </span> 
            @endif

            {{-- 5. Tampilkan Lokasi --}}
            @if(request('lokasi')) 
                <span class="badge bg-primary">Lokasi Terdekat</span> 
            @endif
        </div>
    @endif

    {{-- GRID PRODUK --}}
    <div class="row g-3">
        @forelse ($products as $product)
            <div class="col-6 col-md-3">
                <div class="product-card h-100 d-flex flex-column shadow-sm">
                    <div class="position-relative">
                        <img src="{{ Str::startsWith($product->gambar, 'http') ? $product->gambar : ($product->gambar ? asset('storage/products/' . $product->gambar) : 'https://placehold.co/400x300/eeeeee/999?text=' . urlencode($product->nama_produk)) }}" alt="{{ $product->nama_produk }}" style="height: 180px; object-fit: cover; width: 100%;">
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

                        <div class="mt-auto pt-2 border-top">
                            <p class="fw-bold mb-1 text-accent fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            <small class="text-muted d-block mb-3">Sisa Stok: {{ $product->stok }}</small>
                            <a href="{{ route('produk.show', $product->id_produk) }}" class="btn btn-pk btn-sm w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-search fs-1 d-block mb-3 text-secondary"></i>
                <h5 class="fw-bold">Produk Tidak Ditemukan</h5>
                <p>Maaf, kami tidak dapat menemukan produk yang sesuai dengan filter atau pencarian Anda.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection