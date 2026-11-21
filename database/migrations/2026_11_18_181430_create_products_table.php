<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_produk');
            $table->unsignedBigInteger('id_penjual');
            $table->unsignedBigInteger('id_kategori');
            $table->string('nama_produk', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->string('gambar')->nullable();
            $table->enum('status_penilaian', ['sudah_dinilai', 'belum_dinilai'])->default('belum_dinilai');
            $table->boolean('konfirmasi_izin')->default(false);
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak', 'nonaktif'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('id_penjual')->references('id_user')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_kategori')->references('id_kategori')->on('categories')->onUpdate('cascade')->onDelete('restrict');
            $table->index('id_penjual');
            $table->index('id_kategori');
            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};