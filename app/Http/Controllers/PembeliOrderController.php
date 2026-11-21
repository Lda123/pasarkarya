<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembeliOrderController extends Controller
{
    public function create(Product $product)
    {
        if (Auth::user()->role !== 'pembeli') {
            abort(403, 'Hanya pembeli yang dapat melakukan pemesanan.');
        }

        if ($product->status_verifikasi !== 'disetujui' || $product->stok <= 0) {
            return redirect()->route('home')
                ->with('error', 'Produk tidak tersedia atau stok habis.');
        }

        return view('pembeli.checkout', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        if (Auth::user()->role !== 'pembeli') {
            abort(403);
        }

        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $product->stok,
            'catatan' => 'nullable|string|max:255',
        ], [
            'jumlah.max' => 'Jumlah pesanan melebihi sisa stok yang ada.'
        ]);

        try {
            DB::beginTransaction();

            $kodePesanan = 'PK-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $totalHarga = $product->harga * $request->jumlah;

            $order = Order::create([
                'kode_pesanan' => $kodePesanan,
                'id_pembeli' => Auth::id(),
                'id_penjual' => $product->id_penjual,
                'tanggal_pesanan' => now(),
                'total_harga' => $totalHarga,
                'metode_pembayaran' => 'COD', // Hardcode COD
                'status_pembayaran' => 'belum_bayar',
                'status_pesanan' => 'menunggu_konfirmasi',
                'catatan' => $request->catatan,
            ]);

            OrderItem::create([
                'id_pesanan' => $order->id_pesanan,
                'id_produk' => $product->id_produk,
                'nama_produk_snapshot' => $product->nama_produk,
                'harga_snapshot' => $product->harga,
                'jumlah' => $request->jumlah,
                'subtotal' => $totalHarga,
            ]);

            $product->decrement('stok', $request->jumlah);

            DB::commit();

            return redirect()->route('pembeli.dashboard')
                ->with('success', 'Pesanan berhasil dibuat! Kode Pesanan: ' . $kodePesanan);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        if ((int) $order->id_pembeli !== (int) Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        try {
            $berhasil = DB::transaction(function () use ($order) {
                $pesanan = Order::where('id_pesanan', $order->id_pesanan)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!in_array($pesanan->status_pesanan, [
                    'menunggu_konfirmasi',
                    'diterima',
                    'diproses'
                ])) {
                    return false;
                }

                if (!$pesanan->stok_dikembalikan) {
                    $items = OrderItem::where('id_pesanan', $pesanan->id_pesanan)->get();

                    foreach ($items as $item) {
                        $product = Product::where('id_produk', $item->id_produk)
                            ->lockForUpdate()
                            ->first();

                        if ($product) {
                            $product->increment('stok', $item->jumlah);
                        }
                    }

                    $pesanan->stok_dikembalikan = true;
                }

                $pesanan->status_pesanan = 'dibatalkan';
                $pesanan->save();

                return true;
            });

            if (!$berhasil) {
                return back()->with(
                    'error',
                    'Pesanan tidak dapat dibatalkan karena statusnya sudah berubah.'
                );
            }

            return back()->with('success', 'Pesanan berhasil dibatalkan.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }

    public function complete(Order $order)
    {
        if ((int) $order->id_pembeli !== (int) Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $berhasil = DB::transaction(function () use ($order) {
            $pesanan = Order::where('id_pesanan', $order->id_pesanan)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pesanan->status_pesanan !== 'siap_diantar') {
                return false;
            }

            $pesanan->status_pesanan = 'selesai';
            $pesanan->save();

            return true;
        });

        if (!$berhasil) {
            return back()->with(
                'error',
                'Pesanan belum siap dikonfirmasi atau sudah berubah status.'
            );
        }

        return back()->with(
            'success',
            'Pesanan berhasil dikonfirmasi selesai. Terima kasih sudah berbelanja!'
        );
    }
}