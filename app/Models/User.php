<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'status_akun',
        'kota_kabupaten',
        'kecamatan',
        'desa_kelurahan',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_penjual', 'id_user');
    }

    public function ordersAsBuyer()
    {
        return $this->hasMany(Order::class, 'id_pembeli', 'id_user');
    }

    public function ordersAsSeller()
    {
        return $this->hasMany(Order::class, 'id_penjual', 'id_user');
    }
}
