<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_produk');
            $table->string('nama_produk_snapshot', 150);
            $table->decimal('harga_snapshot', 12, 2);
            $table->integer('jumlah');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('products')->onUpdate('cascade')->onDelete('restrict');
            $table->index('id_pesanan');
            $table->index('id_produk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};