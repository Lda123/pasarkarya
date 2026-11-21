<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id_kategori';

    protected $fillable = ['nama_kategori', 'deskripsi', 'status'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id_kategori', 'id_kategori');
    }
}