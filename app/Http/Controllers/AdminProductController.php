<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index()
    {
        // Hanya tampilkan produk yang statusnya 'menunggu'
        $products = Product::with(['category', 'seller'])
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->get();
            
        return view('admin.produk.index', compact('products'));
    }

    public function approve(Product $product)
    {
        $product->update([
            'status_verifikasi' => 'disetujui',
            'catatan_admin' => null // Bersihkan catatan jika sebelumnya pernah ditolak
        ]);

        return redirect()->back()->with('success', 'Produk berhasil disetujui dan akan tampil di katalog.');
    }

    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:255'
        ]);

        $product->update([
            'status_verifikasi' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->back()->with('success', 'Produk ditolak dan catatan telah dikirim ke mahasiswa.');
    }
}