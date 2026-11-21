<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id_pesanan';
    
    protected $fillable = [
        'kode_pesanan',
        'id_pembeli',
        'id_penjual',
        'tanggal_pesanan',
        'total_harga',
        'metode_pembayaran',   
        'status_pembayaran',   
        'status_pesanan',
        'catatan',
        'stok_dikembalikan',
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
    ];

    protected $casts = [
        'tanggal_pesanan' => 'datetime',
        'tanggal_permintaan' => 'date',
        'waktu_permintaan' => 'datetime:H:i',
        'stok_dikembalikan' => 'boolean',
        'total_harga' => 'decimal:2',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'id_pembeli', 'id_user');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'id_penjual', 'id_user');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_pesanan', 'id_pesanan');
    }
}