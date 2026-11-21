<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan keranjang pembeli
    public function index()
    {
        $carts = Cart::with(['product.seller', 'product.category'])
            ->where('id_pembeli', Auth::id())
            ->get();

        return view('pembeli.keranjang.index', compact('carts'));
    }

    // Menambahkan produk ke keranjang
    public function store(Request $request, $id)
    {
        $product = Product::where('status_verifikasi', 'disetujui')
            ->where('stok', '>', 0)
            ->findOrFail($id);

        $cart = Cart::where('id_pembeli', Auth::id())
            ->where('id_produk', $product->id_produk)
            ->first();

        $jumlahBaru = ($cart ? $cart->jumlah : 0) + 1;

        if ($jumlahBaru > $product->stok) {
            return back()->with('error', 'Jumlah produk di keranjang melebihi stok tersedia.');
        }

        if ($cart) {
            $cart->update([
                'jumlah' => $jumlahBaru,
            ]);
        } else {
            Cart::create([
                'id_pembeli' => Auth::id(),
                'id_produk' => $product->id_produk,
                'jumlah' => 1,
            ]);
        }

        return redirect()->route('pembeli.keranjang.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // Mengubah jumlah produk di keranjang
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('id_pembeli', Auth::id())
            ->findOrFail($id);

        $product = $cart->product;

        if (!$product || $request->jumlah > $product->stok) {
            return back()->with('error', 'Jumlah melebihi stok produk yang tersedia.');
        }

        $cart->update([
            'jumlah' => $request->jumlah,
        ]);

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    // Menghapus produk dari keranjang
    public function destroy($id)
    {
        $cart = Cart::where('id_pembeli', Auth::id())
            ->findOrFail($id);

        $cart->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
