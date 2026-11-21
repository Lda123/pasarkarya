@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4">
                <h3 class="fw-bold text-primary-pk">Profil Saya</h3>
                <p class="text-muted">
                    Lengkapi informasi kontak dan lokasi untuk memudahkan transaksi PasarKarya.
                </p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="fw-bold text-primary-pk mb-3">
                            Informasi Akun
                        </h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" class="form-control" value="{{ $user->nama }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="no_hp" class="form-label fw-semibold">
                                Nomor WhatsApp
                            </label>
                            <input type="text" name="no_hp" id="no_hp"
                                class="form-control"
                                value="{{ old('no_hp', $user->no_hp) }}"
                                placeholder="Contoh: 081234567890"
                                maxlength="20" required>
                        </div>

                        <hr>

                        <h5 class="fw-bold text-primary-pk mb-2">
                            Lokasi Pengguna
                        </h5>

                        <p class="text-muted small mb-3">
                            Lokasi digunakan untuk membantu pembeli menemukan produk dari wilayah terdekat.
                        </p>

                        {{-- PANGGIL PARTIAL DROPDOWN WILAYAH --}}
                        @include('partials.region-select')

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-outline-secondary">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-pk px-4">
                                <i class="bi bi-save me-1"></i>
                                Simpan Profil
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection