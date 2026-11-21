@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4 border-bottom-0">
                    <h4 class="fw-bold text-primary-pk mb-0">Tambah Produk Baru</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('mahasiswa.produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="id_kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id_kategori }}" {{ old('id_kategori') == $cat->id_kategori ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Stok</label>
                                <input type="number" name="stok" class="form-control" value="{{ old('stok') }}" required min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Gambar Produk</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="mb-4 p-3 bg-light rounded border">
                            <h6 class="fw-bold mb-3">Konfirmasi Persyaratan</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="status_penilaian" id="sudah_dinilai" value="sudah_dinilai" required>
                                <label class="form-check-label" for="sudah_dinilai">Produk sudah dinilai (UAS/Pameran)</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="status_penilaian" id="belum_dinilai" value="belum_dinilai" required>
                                <label class="form-check-label" for="belum_dinilai">Produk belum dinilai</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="konfirmasi_izin" id="konfirmasi_izin" value="1" required>
                                <label class="form-check-label" for="konfirmasi_izin">
                                    Saya menyatakan memiliki hak/izin untuk menjual produk ini.
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mahasiswa.produk.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-pk px-4">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection