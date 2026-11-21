<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id_keranjang');

            $table->unsignedBigInteger('id_pembeli');
            $table->unsignedBigInteger('id_produk');

            $table->integer('jumlah')->default(1);

            $table->timestamps();

            // Relasi ke tabel users
            $table->foreign('id_pembeli')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();

            // Relasi ke tabel products
            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('products')
                ->cascadeOnDelete();

            // Satu pembeli hanya memiliki satu baris
            // untuk produk yang sama
            $table->unique(['id_pembeli', 'id_produk']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
