<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MahasiswaProductController extends Controller
{
    public function index()
    {
        // Ambil produk milik user yang login
        $products = Product::with('category')->where('id_penjual', Auth::id())->latest()->get();
        return view('mahasiswa.produk.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'aktif')->get();
        return view('mahasiswa.produk.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'id_kategori' => 'required|exists:categories,id_kategori',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
            'status_penilaian' => 'required|in:sudah_dinilai,belum_dinilai',
            'konfirmasi_izin' => 'required|boolean',
        ]);

        $data = $request->except('gambar');
        $data['id_penjual'] = Auth::id();
        $data['status_verifikasi'] = 'menunggu'; // Otomatis menunggu saat dibuat

        // Proses upload gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::slug($request->nama_produk) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public'); // Simpan di storage/app/public/products
            $data['gambar'] = $filename;
        }

        Product::create($data);

        return redirect()->route('mahasiswa.produk.index')->with('success', 'Produk berhasil ditambahkan dan menunggu verifikasi Admin.');
    }

    public function edit(Product $product)
    {
        // PERBAIKAN: Gunakan (int) agar tipe data dipaksa menjadi angka
        if ((int)$product->id_penjual !== (int)Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit produk ini.');
        }
        
        $categories = Category::where('status', 'aktif')->get();
        return view('mahasiswa.produk.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // PERBAIKAN: Gunakan (int)
        if ((int)$product->id_penjual !== (int)Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah produk ini.');
        }

        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'id_kategori' => 'required|exists:categories,id_kategori',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_penilaian' => 'required|in:sudah_dinilai,belum_dinilai',
            'konfirmasi_izin' => 'required|boolean',
        ]);

        $data = $request->except('gambar');
        
        if ($product->status_verifikasi == 'ditolak') {
            $data['status_verifikasi'] = 'menunggu';
            $data['catatan_admin'] = null;
        }

        if ($request->hasFile('gambar')) {
            if ($product->gambar && Storage::disk('public')->exists('products/' . $product->gambar)) {
                Storage::disk('public')->delete('products/' . $product->gambar);
            }
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::slug($request->nama_produk) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            $data['gambar'] = $filename;
        }

        $product->update($data);

        return redirect()->route('mahasiswa.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ((int)$product->id_penjual !== (int)Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus produk ini.');
        }

        try {
            // Hapus file gambar dari storage agar tidak menjadi sampah
            if ($product->gambar && !Str::startsWith($product->gambar, 'http')) {
                if (Storage::disk('public')->exists('products/' . $product->gambar)) {
                    Storage::disk('public')->delete('products/' . $product->gambar);
                }
            }
            
            // Hapus permanen dari database
            $product->delete();
            
            return redirect()->route('mahasiswa.produk.index')->with('success', 'Produk berhasil dihapus permanen.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika gagal karena produk sudah ada di tabel order_items (riwayat pesanan)
            return redirect()->route('mahasiswa.produk.index')->with('error', 'Produk gagal dihapus karena sudah ada dalam riwayat pesanan pembeli. Silakan nonaktifkan saja.');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id_produk'
        ]);

        $products = Product::whereIn('id_produk', $request->ids)
                           ->where('id_penjual', Auth::id())
                           ->get();
                           
        $berhasil = 0;
        $gagal = 0;

        foreach ($products as $product) {
            try {
                // Hapus file gambar
                if ($product->gambar && !Str::startsWith($product->gambar, 'http')) {
                    if (Storage::disk('public')->exists('products/' . $product->gambar)) {
                        Storage::disk('public')->delete('products/' . $product->gambar);
                    }
                }
                
                $product->delete();
                $berhasil++;
            } catch (\Illuminate\Database\QueryException $e) {
                $gagal++;
            }
        }

        if ($gagal > 0) {
            return redirect()->route('mahasiswa.produk.index')->with('success', "$berhasil produk berhasil dihapus permanen. Namun $gagal produk gagal dihapus karena sudah ada di riwayat pesanan.");
        }

        return redirect()->route('mahasiswa.produk.index')->with('success', 'Semua produk yang dipilih berhasil dihapus permanen.');
    }
}