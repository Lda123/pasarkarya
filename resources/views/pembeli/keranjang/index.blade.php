@extends('layouts.app')

@section('title', 'Keranjang Saya')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold">Keranjang Saya</h2>
        <p class="text-muted">Pilih produk yang ingin kamu pesan, lalu lanjut ke checkout.</p>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if($carts->isEmpty())

        <div class="card border-0 shadow-sm text-center p-5">
            <div class="mb-3">
                <i class="bi bi-cart-x display-3 text-secondary"></i>
            </div>
            <h5 class="fw-bold">Keranjang Masih Kosong</h5>
            <p class="text-muted">Kamu belum menambahkan produk ke keranjang.</p>
            <a href="{{ route('home') }}#daftar-produk" class="btn btn-accent">
                <i class="bi bi-bag me-1"></i> Mulai Belanja
            </a>
        </div>

    @else

        {{-- Bungkus dengan form GET untuk mengirim ID yang dicentang --}}
        <form action="{{ route('pembeli.checkout.keranjang.create') }}" method="GET">
            <div class="row g-4">

                {{-- Daftar Produk --}}
                <div class="col-lg-8">

                    @foreach($carts as $cart)
                        @php
                            $product = $cart->product;
                            $subtotal = $product->harga * $cart->jumlah;
                            
                            // LOGIKA GAMBAR (Mendukung Link Internet & File Lokal)
                            $gambar = $product->gambar;
                            if (Str::startsWith($gambar, 'http')) {
                                $imgSrc = $gambar;
                            } elseif ($gambar) {
                                $imgSrc = asset('storage/' . $gambar);
                            } else {
                                $imgSrc = 'https://placehold.co/150x150/eeeeee/999?text=No+Image';
                            }
                        @endphp

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-3">
                                    
                                    {{-- 1. CHECKBOX (Gunakan id_keranjang) --}}
                                    <div class="form-check m-0">
                                        <input type="checkbox" 
                                               name="ids[]" 
                                               value="{{ $cart->id_keranjang }}" 
                                               class="form-check-input cart-checkbox" 
                                               data-price="{{ (int) $subtotal }}"
                                               style="width: 1.5rem; height: 1.5rem; cursor: pointer;">
                                    </div>

                                    {{-- 2. GAMBAR PRODUK --}}
                                    <div style="flex-shrink: 0;">
                                        <img src="{{ $imgSrc }}"
                                             alt="{{ $product->nama_produk }}"
                                             class="rounded"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>

                                    {{-- 3. INFORMASI PRODUK --}}
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $product->nama_produk }}</h6>
                                        <p class="text-muted small mb-0">
                                            {{ $product->category->nama_kategori ?? 'Tanpa Kategori' }} | Penjual: {{ $product->seller->nama ?? '-' }}
                                        </p>
                                        <p class="fw-semibold text-primary mb-0 mt-1">
                                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    {{-- 4. JUMLAH & AKSI --}}
                                    <div style="min-width: 150px;">
                                        {{-- Form update jumlah (Gunakan id_keranjang) --}}
                                        <form action="{{ route('pembeli.keranjang.update', $cart->id_keranjang) }}" method="POST" class="mb-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="jumlah" class="form-control"
                                                       value="{{ $cart->jumlah }}" min="1" max="{{ $product->stok }}" required>
                                                <button type="submit" class="btn btn-outline-primary" title="Perbarui">↻</button>
                                            </div>
                                        </form>

                                        {{-- Tombol Hapus (Gunakan id_keranjang) --}}
                                        <button type="submit" 
                                                form="delete-form-{{ $cart->id_keranjang }}"
                                                class="btn btn-outline-danger btn-sm w-100"
                                                onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>

                                        <div class="text-end mt-2">
                                            <small class="text-muted">Subtotal</small>
                                            <div class="fw-bold text-accent">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Ringkasan Keranjang --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Ringkasan</h5>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Item</span>
                                <span class="fw-semibold">{{ $carts->sum('jumlah') }} item</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Total Terpilih</span>
                                <span class="fw-bold text-primary fs-5" id="totalHarga">Rp 0</span>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2" id="btnCheckout" disabled>
                                <i class="bi bi-credit-card me-2"></i> Lanjut ke Checkout
                            </button>

                            <a href="{{ route('home') }}#daftar-produk" class="btn btn-outline-secondary w-100 mt-3">
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        {{-- Form Hapus Tersembunyi (Gunakan id_keranjang) --}}
        @foreach($carts as $cart)
            <form id="delete-form-{{ $cart->id_keranjang }}" 
                  action="{{ route('pembeli.keranjang.destroy', $cart->id_keranjang) }}" 
                  method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

    @endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes   = document.querySelectorAll('.cart-checkbox');
        const totalHargaEl = document.getElementById('totalHarga');
        const btnCheckout  = document.getElementById('btnCheckout');

        function updateTotal() {
            let total = 0;
            let checkedCount = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.getAttribute('data-price')) || 0;
                    checkedCount++;
                }
            });

            totalHargaEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            btnCheckout.disabled = checkedCount === 0;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
        
        updateTotal();
    });
</script>
@endpush