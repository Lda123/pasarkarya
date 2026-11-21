<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 'aktif')->get();
        $query = Product::with(['category', 'seller'])->approved()->inStock();

        // Home hanya menampilkan 8 produk terbaru, tanpa filter pencarian
        $products = $query->latest()->limit(8)->get();

        return view('home', compact('categories', 'products'));
    }

    public function catalog(Request $request)
    {
        $categories = Category::where('status', 'aktif')->get();
        $query = Product::with(['category', 'seller'])->approved()->inStock();

        // 1. FITUR SEARCH
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // 2. FITUR FILTER KATEGORI (Mendukung 1 kategori dari Home, atau banyak dari Checkbox)
        if ($request->filled('category')) {
            $query->where('id_kategori', $request->category);
        }
        if ($request->filled('categories') && is_array($request->categories)) {
            $query->whereIn('id_kategori', $request->categories);
        }

        // 3. FITUR FILTER RANGE HARGA
        if ($request->filled('min_price')) {
            $query->where('harga', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }

        $buyer = $request->user();

        // 4. FITUR FILTER LOKASI
        if ($request->input('lokasi') === 'terdekat' && $buyer && $buyer->role === 'pembeli') {
            $products = $query->get();

            $normalize = function ($value) {
                return mb_strtolower(trim((string) $value));
            };

            $buyerCity = $normalize($buyer->kota_kabupaten);
            $buyerDistrict = $normalize($buyer->kecamatan);
            $buyerVillage = $normalize($buyer->desa_kelurahan);

            $getLocationRank = function ($seller) use ($buyerCity, $buyerDistrict, $buyerVillage, $normalize) {
                if (!$seller || $buyerCity === '') return 3;
                $sellerCity = $normalize($seller->kota_kabupaten);
                $sellerDistrict = $normalize($seller->kecamatan);
                $sellerVillage = $normalize($seller->desa_kelurahan);

                if ($sellerCity !== $buyerCity) return 3;
                if ($buyerDistrict !== '' && $sellerDistrict === $buyerDistrict) {
                    if ($buyerVillage !== '' && $sellerVillage === $buyerVillage) return 0;
                    return 1;
                }
                return 2;
            };

            $products = $products->filter(function ($product) use ($getLocationRank) {
                return $getLocationRank($product->seller) < 3;
            });

            $products = $products->sort(function ($a, $b) use ($getLocationRank) {
                $rankA = $getLocationRank($a->seller);
                $rankB = $getLocationRank($b->seller);
                if ($rankA !== $rankB) return $rankA <=> $rankB;
                $dateA = $a->created_at?->timestamp ?? 0;
                $dateB = $b->created_at?->timestamp ?? 0;
                return $dateB <=> $dateA;
            })->values();
            
        } else {
            // Tampilkan semua produk jika tidak ada filter lokasi
            $products = $query->latest()->get(); 
        }

        return view('catalog', compact('categories', 'products'));
    }
}