@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold">Checkout Pesanan</h2>
        <p class="text-muted">Periksa pesanan dan lengkapi alamat pengambilan.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- TAMBAHAN: tampilkan error dari controller --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pembeli.checkout.keranjang.store') }}" method="POST">
        @csrf

        {{-- TAMBAHAN WAJIB: kirim ID keranjang yang dicentang --}}
        @foreach($carts as $cart)
            <input type="hidden" name="ids[]" value="{{ $cart->id_keranjang }}">
        @endforeach

        <div class="row g-4">

            {{-- Informasi Pembeli --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-person-circle me-2"></i>Informasi Pembeli
                        </h5>

                        <div class="mb-3">
                            <label class="form-label">Nama Pembeli</label>
                            <input type="text" class="form-control"
                                   value="{{ $user->nama }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp</label>
                            <input type="text" class="form-control"
                                   value="{{ $user->no_hp }}" readonly>
                            <small class="text-muted">
                                Nomor diambil dari profil. Ubah melalui halaman profil jika tidak sesuai.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Alamat Pembeli --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-geo-alt me-2"></i>Alamat Pembeli
                        </h5>

                        <div class="alert alert-light border small">
                            Kota, kecamatan, dan desa/kelurahan diambil otomatis dari profil.
                            Lengkapi detail alamat di bawah ini.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kota/Kabupaten</label>
                            <input type="text" class="form-control"
                                   value="{{ $user->kota_kabupaten }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" class="form-control"
                                   value="{{ $user->kecamatan }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Desa/Kelurahan</label>
                            <input type="text" class="form-control"
                                   value="{{ $user->desa_kelurahan }}" readonly>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Alamat Jalan / Nama Jalan</label>
                            <textarea name="alamat_jalan" class="form-control"
                                      rows="2" required>{{ old('alamat_jalan') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RT</label>
                                <input type="text" name="rt" class="form-control"
                                       value="{{ old('rt') }}" maxlength="5" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">RW</label>
                                <input type="text" name="rw" class="form-control"
                                       value="{{ old('rw') }}" maxlength="5" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control"
                                   value="{{ old('kode_pos') }}" maxlength="10" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Patokan Lokasi (Opsional)</label>
                            <textarea name="patokan_lokasi" class="form-control"
                                      rows="2">{{ old('patokan_lokasi') }}</textarea>
                            <small class="text-muted">
                                Contoh: dekat masjid, depan minimarket, atau samping balai desa.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Jadwal Pengambilan --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-calendar-event me-2"></i>Permintaan Jadwal
                        </h5>

                        <div class="alert alert-warning small">
                            Tanggal dan waktu ini merupakan permintaan pembeli.
                            Penjual akan mengonfirmasi ketersediaannya melalui WhatsApp.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal_permintaan"
                                       class="form-control"
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ old('tanggal_permintaan') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu</label>
                                <input type="time" name="waktu_permintaan"
                                       class="form-control"
                                       value="{{ old('waktu_permintaan') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pesanan --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

                        @foreach($carts as $cart)
                            @php
                                $product = $cart->product;
                                $subtotal = $product->harga * $cart->jumlah;
                            @endphp

                            <div class="d-flex justify-content-between gap-3 mb-3">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $product->nama_produk }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $cart->jumlah }} ×
                                        Rp{{ number_format($product->harga, 0, ',', '.') }}
                                    </small>
                                    <div class="small text-muted">
                                        Penjual: {{ $product->seller->nama ?? '-' }}
                                    </div>
                                </div>

                                <div class="fw-semibold text-nowrap">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </div>
                            </div>

                            <hr>
                        @endforeach

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total Pesanan</span>
                            <span class="fw-bold text-primary fs-5">
                                Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}
                            </span>
                        </div>

                        <input type="hidden" name="metode_pembayaran" value="COD">
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-1"></i> Metode Pembayaran: <strong>Bayar di Tempat (COD)</strong>. Silakan bayar langsung ke penjual saat mengambil barang.
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-check-circle me-2"></i>
                            Buat Pesanan
                        </button>

                        <a href="{{ route('pembeli.keranjang.index') }}"
                           class="btn btn-outline-secondary w-100 mt-2">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection