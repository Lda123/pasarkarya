<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['nama_kategori' => 'Kerajinan', 'deskripsi' => 'Produk kerajinan tangan dan dekorasi']);
        Category::create(['nama_kategori' => 'Alat Sederhana', 'deskripsi' => 'Alat bantu dan perkakas hasil rancangan mahasiswa']);
        Category::create(['nama_kategori' => 'Prototipe', 'deskripsi' => 'Prototipe produk atau perangkat']);
        Category::create(['nama_kategori' => 'Media Pembelajaran', 'deskripsi' => 'Alat peraga dan media belajar']);
        Category::create(['nama_kategori' => 'Produk Kreatif', 'deskripsi' => 'Produk kreatif lainnya']);
    }
}