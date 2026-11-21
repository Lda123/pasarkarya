<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Salinan alamat pembeli saat checkout
            $table->string('kota_kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa_kelurahan')->nullable();
            $table->text('alamat_jalan')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('patokan_lokasi')->nullable();

            // Nomor WhatsApp pembeli saat checkout
            $table->string('no_hp_pembeli', 20)->nullable();

            // Jadwal yang diminta pembeli
            $table->date('tanggal_permintaan')->nullable();
            $table->time('waktu_permintaan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'kota_kabupaten',
                'kecamatan',
                'desa_kelurahan',
                'alamat_jalan',
                'rt',
                'rw',
                'kode_pos',
                'patokan_lokasi',
                'no_hp_pembeli',
                'tanggal_permintaan',
                'waktu_permintaan',
            ]);
        });
    }
};
