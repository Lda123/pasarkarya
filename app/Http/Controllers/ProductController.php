<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        // Ambil produk beserta relasi kategori dan penjualnya
        // Pastikan produk sudah disetujui agar tidak bisa diakses langsung via URL
        $product = Product::with(['category', 'seller'])
            ->where('status_verifikasi', 'disetujui')
            ->findOrFail($id);

        return view('produk.detail', compact('product'));
    }
}