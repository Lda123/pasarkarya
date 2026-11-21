@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-primary-pk mb-4">Konfirmasi Pesanan</h3>

            {{-- Tampilkan error validasi --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <img src="{{ Str::startsWith($product->gambar, 'http') ? $product->gambar : ($product->gambar ? asset('storage/' . $product->gambar) : 'https://placehold.co/100x100/eeeeee/999?text=No') }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $product->nama_produk }}</h5>
                            <p class="text-accent fw-bold mb-0">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            <small class="text-muted">Sisa Stok: {{ $product->stok }}</small>
                        </div>
                    </div>

                    <form action="{{ route('pembeli.checkout.store', $product->id_produk) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Pesanan</label>
                            <input type="number" name="jumlah" class="form-control"
                                   value="{{ old('jumlah', 1) }}" min="1" max="{{ $product->stok }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Catatan untuk Penjual (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3"
                                      placeholder="Contoh: Ambil hari Jumat sore...">{{ old('catatan') }}</textarea>
                        </div>

                        {{-- Info Pembayaran COD --}}
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-1"></i> Metode Pembayaran: <strong>Bayar di Tempat (COD)</strong>. Silakan bayar tunai langsung ke penjual saat mengambil barang.
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('produk.show', $product->id_produk) }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-pk px-4 py-2">Konfirmasi & Pesan Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection