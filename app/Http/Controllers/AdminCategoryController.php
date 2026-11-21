<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.kategori.index', compact('categories'));
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'status.required' => 'Status kategori wajib dipilih.',
        ]);

        Category::create($request->only([
            'nama_kategori',
            'deskripsi',
            'status',
        ]));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Memperbarui kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'nama_kategori')
                    ->ignore($category->id_kategori, 'id_kategori'),
            ],
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'status.required' => 'Status kategori wajib dipilih.',
        ]);

        $category->update($request->only([
            'nama_kategori',
            'deskripsi',
            'status',
        ]));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }

        $category->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
