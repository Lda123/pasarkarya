<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_penjual', 'id_kategori', 'nama_produk', 'deskripsi',
        'harga', 'stok', 'gambar', 'status_penilaian',
        'konfirmasi_izin', 'status_verifikasi', 'catatan_admin',
    ];

    protected $casts = [
        'konfirmasi_izin' => 'boolean',
        'harga' => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'id_penjual', 'id_user');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id_kategori');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_produk', 'id_produk');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_verifikasi', 'disetujui');
    }

    public function scopeInStock($query)
    {
        return $query->where('stok', '>', 0);
    }
}