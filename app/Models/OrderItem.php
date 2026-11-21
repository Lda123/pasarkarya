<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pesanan', 'id_produk', 'nama_produk_snapshot',
        'harga_snapshot', 'jumlah', 'subtotal',
    ];

    protected $casts = [
        'harga_snapshot' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id_pesanan');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id_produk');
    }
}