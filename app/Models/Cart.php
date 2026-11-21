<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $primaryKey = 'id_keranjang';

    protected $fillable = [
        'id_pembeli',
        'id_produk',
        'jumlah',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'id_pembeli', 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id_produk');
    }
}

