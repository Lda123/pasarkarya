<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartCheckoutController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        // PERBAIKAN: id menjadi id_keranjang
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:carts,id_keranjang', 
        ], [
            'ids.required' => 'Pilih minimal satu produk untuk di-checkout.',
        ]);

        $carts = Cart::with(['product.seller', 'product.category'])
            ->where('id_pembeli', $user->id_user)
            ->whereIn('id_keranjang', $request->ids) // PERBAIKAN
            ->get();

        if ($carts->isEmpty()) {
            return redirect()
                ->route('pembeli.keranjang.index')
                ->with('error', 'Produk yang dipilih tidak ditemukan di keranjang.');
        }

        foreach ($carts as $cart) {
            if (
                !$cart->product ||
                $cart->product->status_verifikasi !== 'disetujui' ||
                $cart->product->stok < $cart->jumlah
            ) {
                return redirect()
                    ->route('pembeli.keranjang.index')
                    ->with('error', 'Ada produk yang tidak tersedia atau stok tidak mencukupi.');
            }
        }

        $totalKeseluruhan = $carts->sum(function ($cart) {
            return $cart->product->harga * $cart->jumlah;
        });

        return view('pembeli.checkout-keranjang', compact(
            'user',
            'carts',
            'totalKeseluruhan'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (
            !$user->no_hp ||
            !$user->kota_kabupaten ||
            !$user->kecamatan ||
            !$user->desa_kelurahan
        ) {
            return redirect()
                ->route('profile.edit')
                ->with('error', 'Lengkapi nomor WhatsApp dan alamat wilayah di profil sebelum checkout.');
        }

        // HAPUS validasi metode_pembayaran
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:carts,id_keranjang',
            'alamat_jalan' => 'required|string|max:1000',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'kode_pos' => 'required|string|max:10',
            'patokan_lokasi' => 'nullable|string|max:255',
            'tanggal_permintaan' => 'required|date|after_or_equal:today',
            'waktu_permintaan' => 'required|date_format:H:i',
        ]);

        try {
            $kodePesanan = [];

            DB::transaction(function () use ($user, $validated, &$kodePesanan) {
                $carts = Cart::where('id_pembeli', $user->id_user)
                    ->whereIn('id_keranjang', $validated['ids']) // PERBAIKAN
                    ->lockForUpdate()
                    ->get();

                if ($carts->isEmpty()) {
                    throw new \Exception('Keranjang yang dipilih kosong.');
                }

                $produkPerPenjual = [];

                foreach ($carts as $cart) {
                    $product = Product::where('id_produk', $cart->id_produk)
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$product ||
                        $product->status_verifikasi !== 'disetujui' ||
                        $product->stok < $cart->jumlah
                    ) {
                        throw new \Exception(
                            'Produk ' . ($product->nama_produk ?? '') .
                            ' tidak tersedia atau stok tidak mencukupi.'
                        );
                    }

                    $produkPerPenjual[$product->id_penjual][] = [
                        'product' => $product,
                        'jumlah' => $cart->jumlah,
                    ];
                }

                foreach ($produkPerPenjual as $idPenjual => $items) {
                    $totalHarga = 0;
                    foreach ($items as $item) {
                        $totalHarga += $item['product']->harga * $item['jumlah'];
                    }

                    $kode = 'PK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

                    // HAPUS logika VA, langsung isi COD
                    $order = Order::create([
                        'kode_pesanan' => $kode,
                        'id_pembeli' => $user->id_user,
                        'id_penjual' => $idPenjual,
                        'tanggal_pesanan' => now(),
                        'total_harga' => $totalHarga,
                        'metode_pembayaran' => 'COD', // Hardcode COD
                        'status_pembayaran' => 'belum_bayar',
                        'status_pesanan' => 'menunggu_konfirmasi',
                        'kota_kabupaten' => $user->kota_kabupaten,
                        'kecamatan' => $user->kecamatan,
                        'desa_kelurahan' => $user->desa_kelurahan,
                        'alamat_jalan' => $validated['alamat_jalan'],
                        'rt' => $validated['rt'],
                        'rw' => $validated['rw'],
                        'kode_pos' => $validated['kode_pos'],
                        'patokan_lokasi' => $validated['patokan_lokasi'] ?? null,
                        'no_hp_pembeli' => $user->no_hp,
                        'tanggal_permintaan' => $validated['tanggal_permintaan'],
                        'waktu_permintaan' => $validated['waktu_permintaan'],
                    ]);

                    foreach ($items as $item) {
                        $product = $item['product'];
                        $jumlah = $item['jumlah'];
                        $subtotal = $product->harga * $jumlah;

                        OrderItem::create([
                            'id_pesanan' => $order->id_pesanan,
                            'id_produk' => $product->id_produk,
                            'nama_produk_snapshot' => $product->nama_produk,
                            'harga_snapshot' => $product->harga,
                            'jumlah' => $jumlah,
                            'subtotal' => $subtotal,
                        ]);

                        $product->decrement('stok', $jumlah);
                    }

                    $kodePesanan[] = $kode;
                }

                Cart::where('id_pembeli', $user->id_user)
                    ->whereIn('id_keranjang', $validated['ids']) // PERBAIKAN
                    ->delete();
            });

            // Langsung ke dashboard (karena COD)
            return redirect()
                ->route('pembeli.dashboard')
                ->with('success', 'Pesanan COD berhasil dibuat! Kode pesanan: ' . implode(', ', $kodePesanan));

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}