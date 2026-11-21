{{-- resources/views/partials/region-select.blade.php --}}

<div class="mb-3">
    <label for="kota_kabupaten" class="form-label fw-semibold">Kota/Kabupaten <span class="text-danger">*</span></label>
    <select name="kota_kabupaten" id="kota_kabupaten" class="form-select" required>
        <option value="">-- Pilih Kota/Kabupaten --</option>
    </select>
</div>

<div class="mb-3">
    <label for="kecamatan" class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
    <select name="kecamatan" id="kecamatan" class="form-select" required>
        <option value="">-- Pilih Kecamatan --</option>
    </select>
</div>

<div class="mb-4">
    <label for="desa_kelurahan" class="form-label fw-semibold">Desa/Kelurahan <span class="text-danger">*</span></label>
    <select name="desa_kelurahan" id="desa_kelurahan" class="form-select" required>
        <option value="">-- Pilih Desa/Kelurahan --</option>
    </select>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kotaSelect = document.getElementById('kota_kabupaten');
        const kecamatanSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa_kelurahan');

        // Ambil nilai lama (untuk form edit atau validasi gagal)
        const oldKota = "{{ old('kota_kabupaten', $user->kota_kabupaten ?? '') }}";
        const oldKecamatan = "{{ old('kecamatan', $user->kecamatan ?? '') }}";
        const oldDesa = "{{ old('desa_kelurahan', $user->desa_kelurahan ?? '') }}";

        // 1. Muat data Kota saat halaman dibuka
        fetch('/api/regions/kota')
            .then(res => res.json())
            .then(data => {
                data.forEach(kota => {
                    const opt = new Option(kota, kota);
                    if (kota === oldKota) opt.selected = true;
                    kotaSelect.add(opt);
                });

                // Jika ada data kota sebelumnya (edit profil / gagal validasi), muat kecamatannya
                if (oldKota) {
                    loadKecamatan(oldKota, oldKecamatan, oldDesa);
                }
            })
            .catch(err => console.error("Error load kota:", err));

        // 2. Event saat Kota diubah
        kotaSelect.addEventListener('change', function () {
            kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
            if (this.value) {
                loadKecamatan(this.value);
            }
        });

        // 3. Event saat Kecamatan diubah
        kecamatanSelect.addEventListener('change', function () {
            desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
            if (this.value && kotaSelect.value) {
                loadDesa(kotaSelect.value, this.value);
            }
        });

        // Fungsi ambil data Kecamatan
        function loadKecamatan(kota, selectedKecamatan = '', selectedDesa = '') {
            fetch(`/api/regions/kecamatan?kota=${encodeURIComponent(kota)}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(kec => {
                        const opt = new Option(kec, kec);
                        if (kec === selectedKecamatan) opt.selected = true;
                        kecamatanSelect.add(opt);
                    });
                    // Jika ada data kecamatan sebelumnya, lanjut muat desanya
                    if (selectedKecamatan) {
                        loadDesa(kota, selectedKecamatan, selectedDesa);
                    }
                });
        }

        // Fungsi ambil data Desa
        function loadDesa(kota, kecamatan, selectedDesa = '') {
            fetch(`/api/regions/desa?kota=${encodeURIComponent(kota)}&kecamatan=${encodeURIComponent(kecamatan)}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(desa => {
                        const opt = new Option(desa, desa);
                        if (desa === selectedDesa) opt.selected = true;
                        desaSelect.add(opt);
                    });
                });
        }
    });
</script>
@endpush