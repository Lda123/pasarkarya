<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->string('kode_pesanan', 30)->unique();
            $table->unsignedBigInteger('id_pembeli');
            $table->unsignedBigInteger('id_penjual');
            $table->dateTime('tanggal_pesanan');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status_pesanan', [
                'menunggu_konfirmasi', 'diterima', 'ditolak', 'diproses',
                'siap_diantar', 'selesai', 'dibatalkan'
            ])->default('menunggu_konfirmasi');
            $table->text('catatan')->nullable();
            $table->boolean('stok_dikembalikan')->default(false);
            $table->timestamps();

            $table->foreign('id_pembeli')->references('id_user')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_penjual')->references('id_user')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->index('id_pembeli');
            $table->index('id_penjual');
            $table->index('status_pesanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};