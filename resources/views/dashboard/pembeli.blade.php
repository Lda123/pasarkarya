@extends('layouts.app')

@section('title', 'Dashboard Pembeli')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold text-primary-pk mb-4">Riwayat Pesanan Saya</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
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
                        <th>Produk</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pesananSaya as $o)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $o->kode_pesanan }}</div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($o->tanggal_pesanan)->format('d M Y') }}
                            </small>
                        </td>

                        <td>
                            @foreach($o->items as $item)
                                <div>{{ $item->nama_produk_snapshot }} ({{ $item->jumlah }}x)</div>
                            @endforeach
                        </td>

                        <td class="fw-bold text-accent">
                            Rp {{ number_format($o->total_harga, 0, ',', '.') }}
                        </td>

                        <td>
                            @if($o->status_pesanan == 'menunggu_konfirmasi')
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                            @elseif($o->status_pesanan == 'diterima')
                                <span class="badge bg-info text-dark">Diterima Penjual</span>
                            @elseif($o->status_pesanan == 'diproses')
                                <span class="badge bg-primary">Sedang Diproses</span>
                            @elseif($o->status_pesanan == 'siap_diantar' || $o->status_pesanan == 'siap_diambil')
                                <span class="badge bg-success">Siap Diambil/Diantar</span>
                            @elseif($o->status_pesanan == 'selesai')
                                <span class="badge bg-secondary">Selesai</span>
                            @elseif($o->status_pesanan == 'dibatalkan')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @elseif($o->status_pesanan == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>

                        <td>
                            {{-- TOMBOL CHAT PENJUAL (WHATSAPP) --}}
                            @php
                                $noHpPenjual = $o->seller->no_hp ?? '';
                                $waNumber = preg_replace('/^0/', '62', $noHpPenjual);
                                $pesan = "Halo, saya " . auth()->user()->nama . ". Saya sudah memesan produk dengan kode " . $o->kode_pesanan . " (COD). Kapan saya bisa mengambil barangnya?";
                            @endphp
                            
                            @if($waNumber)
                                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($pesan) }}" target="_blank" class="btn btn-sm btn-outline-success mb-2 w-100" title="Hubungi Penjual">
                                    <i class="bi bi-whatsapp"></i> Chat Penjual
                                </a>
                            @endif
                            {{-- AKHIR TOMBOL CHAT --}}

                            @if(in_array($o->status_pesanan, ['menunggu_konfirmasi', 'diterima', 'diproses']))
                                {{-- Tombol Batalkan Pesanan --}}
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action w-100" 
                                    data-form-id="cancel-form-{{ $o->id_pesanan }}"
                                    data-title="Batalkan Pesanan"
                                    data-heading="Yakin ingin membatalkan pesanan ini?"
                                    data-message="Stok produk akan otomatis dikembalikan ke penjual."
                                    data-icon="bi-exclamation-triangle text-danger"
                                    data-btn-class="btn-danger"
                                    data-btn-text="Ya, Batalkan">
                                    Batalkan
                                </button>
                                
                                <form id="cancel-form-{{ $o->id_pesanan }}" action="{{ route('pembeli.pesanan.cancel', $o->id_pesanan) }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                            @elseif($o->status_pesanan == 'siap_diantar' || $o->status_pesanan == 'siap_diambil')
                                {{-- Tombol Konfirmasi Diterima --}}
                                <button type="button" class="btn btn-sm btn-success btn-action w-100" 
                                    data-form-id="complete-form-{{ $o->id_pesanan }}"
                                    data-title="Konfirmasi Penerimaan"
                                    data-heading="Apakah kamu sudah menerima pesanan ini?"
                                    data-message="Pastikan produk sudah diterima dengan baik. Tindakan ini tidak dapat dibatalkan."
                                    data-icon="bi-check-circle text-success"
                                    data-btn-class="btn-success"
                                    data-btn-text="Ya, Konfirmasi">
                                    Konfirmasi Diterima
                                </button>

                                <form id="complete-form-{{ $o->id_pesanan }}" action="{{ route('pembeli.pesanan.complete', $o->id_pesanan) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-bag-x fs-1 d-block mb-2"></i>
                            Anda belum pernah melakukan pemesanan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI AKSI (GLOBAL) --}}
<div class="modal fade" id="actionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: var(--pk-primary);">
                <h5 class="modal-title fw-bold" id="actionModalTitle">Konfirmasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="bi bi-question-circle" id="actionModalIcon" style="font-size: 3rem;"></i>
                <h5 class="mt-3 fw-bold" id="actionModalHeading">Apakah Anda yakin?</h5>
                <p class="text-muted" id="actionModalMessage">Pesan penjelasan akan muncul di sini.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Kembali</button>
                <button type="button" class="btn px-4" id="btnConfirmAction">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actionModalEl = document.getElementById('actionModal');
        const actionModal = new bootstrap.Modal(actionModalEl);
        const btnConfirmAction = document.getElementById('btnConfirmAction');
        let formToSubmit = null;

        document.querySelectorAll('.btn-action').forEach(btn => {
            btn.addEventListener('click', function() {
                const formId = this.getAttribute('data-form-id');
                const title = this.getAttribute('data-title');
                const heading = this.getAttribute('data-heading');
                const message = this.getAttribute('data-message');
                const iconClass = this.getAttribute('data-icon');
                const btnClass = this.getAttribute('data-btn-class');
                const btnText = this.getAttribute('data-btn-text');

                document.getElementById('actionModalTitle').textContent = title;
                document.getElementById('actionModalHeading').textContent = heading;
                document.getElementById('actionModalMessage').textContent = message;
                
                const iconEl = document.getElementById('actionModalIcon');
                iconEl.className = 'bi ' + iconClass;
                
                btnConfirmAction.className = 'btn px-4 ' + btnClass;
                btnConfirmAction.textContent = btnText;

                formToSubmit = document.getElementById(formId);
                actionModal.show();
            });
        });

        btnConfirmAction.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });
    });
</script>
@endpush