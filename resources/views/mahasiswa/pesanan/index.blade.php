@extends('layouts.app')

@section('title', 'Pesanan Masuk')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary-pk mb-0">Pesanan Masuk</h3>
        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Pembeli & Kontak</th>
                        <th>Produk & Total</th>
                        <th>Status</th>
                        <th width="180" class="text-center">Aksi (Ubah Status)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $o->kode_pesanan }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($o->tanggal_pesanan)->format('d M Y, H:i') }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $o->buyer->nama }}</div>
                            <small class="text-muted d-block mb-2">{{ $o->buyer->no_hp }}</small>
                            
                            {{-- TOMBOL CHAT PEMBELI (WHATSAPP) --}}
                            @php
                                // Prioritaskan no_hp_pembeli dari order, jika kosong ambil dari profil user
                                $noHpPembeli = $o->no_hp_pembeli ?? $o->buyer->no_hp; 
                                $waNumberPembeli = preg_replace('/^0/', '62', $noHpPembeli);
                                $pesanPembeli = "Halo " . $o->buyer->nama . ", saya penjual di PasarKarya. Pesanan Anda dengan kode " . $o->kode_pesanan . " sudah saya terima. Kapan Anda ingin mengambil barangnya?";
                            @endphp

                            @if($waNumberPembeli)
                                <a href="https://wa.me/{{ $waNumberPembeli }}?text={{ urlencode($pesanPembeli) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-whatsapp"></i> Chat Pembeli
                                </a>
                            @endif
                        </td>
                        <td>
                            @foreach($o->items as $item)
                                <div>{{ $item->nama_produk_snapshot }} ({{ $item->jumlah }}x)</div>
                            @endforeach
                            <div class="fw-bold text-accent mt-1">Rp {{ number_format($o->total_harga, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            @if($o->status_pesanan == 'menunggu_konfirmasi')
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                            @elseif($o->status_pesanan == 'diterima')
                                <span class="badge bg-info text-dark">Diterima</span>
                            @elseif($o->status_pesanan == 'diproses')
                                <span class="badge bg-primary">Diproses</span>
                            @elseif($o->status_pesanan == 'siap_diambil' || $o->status_pesanan == 'siap_diantar')
                                <span class="badge bg-success">Siap Diambil</span>
                            @elseif($o->status_pesanan == 'selesai')
                                <span class="badge bg-secondary">Selesai</span>
                            @elseif($o->status_pesanan == 'dibatalkan')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @elseif($o->status_pesanan == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- Form Update Status --}}
                            <form action="{{ route('mahasiswa.pesanan.updateStatus', $o->id_pesanan) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status_pesanan" class="form-select form-select-sm" onchange="this.form.submit()" {{ in_array($o->status_pesanan, ['selesai', 'ditolak', 'dibatalkan']) ? 'disabled' : '' }}>
                                    <option value="menunggu_konfirmasi" {{ $o->status_pesanan == 'menunggu_konfirmasi' ? 'selected' : '' }} disabled>Menunggu</option>
                                    <option value="diterima" {{ $o->status_pesanan == 'diterima' ? 'selected' : '' }}>Terima Pesanan</option>
                                    <option value="ditolak" {{ $o->status_pesanan == 'ditolak' ? 'selected' : '' }}>Tolak Pesanan</option>
                                    <option value="diproses" {{ $o->status_pesanan == 'diproses' ? 'selected' : '' }}>Proses</option>
                                    {{-- Ubah siap_diantar menjadi siap_diambil --}}
                                    <option value="siap_diambil" {{ $o->status_pesanan == 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                            Belum ada pesanan masuk untuk produk Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection