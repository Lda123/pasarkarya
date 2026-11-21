<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaOrderController extends Controller
{
    public function index()
    {
        // Ambil semua pesanan masuk untuk penjual ini
        $orders = Order::with(['buyer', 'items.product'])
            ->where('id_penjual', Auth::id())
            ->latest()
            ->get();
            
        return view('mahasiswa.pesanan.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Pastikan ini pesanan milik mahasiswa yang login
        if ((int)$order->id_penjual !== (int)Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'status_pesanan' => 'required|in:diterima,ditolak,diproses,siap_diantar'
        ]);

        try {
            DB::beginTransaction();

            // Jika pesanan ditolak, kembalikan stok produk!
            if ($request->status_pesanan === 'ditolak' && $order->stok_dikembalikan == 0) {
                foreach ($order->items as $item) {
                    // Kembalikan stok ke tabel produk
                    $item->product->increment('stok', $item->jumlah);
                }
                $order->stok_dikembalikan = 1; // Tandai sudah dikembalikan
            }

            $order->status_pesanan = $request->status_pesanan;
            $order->save();

            DB::commit();

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}