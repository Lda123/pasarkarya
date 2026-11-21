<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $budi = User::where('email', 'budi@pembeli.test')->first();
        $sari = User::where('email', 'sari@mahasiswa.test')->first();
        $produkMiniatur = Product::where('nama_produk', 'Miniatur Rumah Kayu')->first();

        $order = Order::create([
            'kode_pesanan' => 'PK-20260101-0001',
            'id_pembeli' => $budi->id_user,
            'id_penjual' => $sari->id_user,
            'tanggal_pesanan' => now(),
            'total_harga' => 100000,
            'status_pesanan' => 'menunggu_konfirmasi',
            'catatan' => 'Ambil hari Jumat sore.',
        ]);

        OrderItem::create([
            'id_pesanan' => $order->id_pesanan,
            'id_produk' => $produkMiniatur->id_produk,
            'nama_produk_snapshot' => 'Miniatur Rumah Kayu',
            'harga_snapshot' => 50000,
            'jumlah' => 2,
            'subtotal' => 100000,
        ]);
    }
}