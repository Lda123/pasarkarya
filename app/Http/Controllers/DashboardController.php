<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalPengguna = User::count();
        $totalProduk = Product::count();
        $produkMenunggu = Product::where('status_verifikasi', 'menunggu')->count();
        
        return view('dashboard.admin', compact('totalPengguna', 'totalProduk', 'produkMenunggu'));
    }

    public function mahasiswa()
    {
        /** @var \App\Models\User $user */ // <-- TAMBAHKAN BARIS INI UNTUK VS CODE
        $user = Auth::user();
        
        // Sekarang VS Code akan mengenali fungsi products()
        $produkSaya = $user->products(); 
        
        $totalProduk = $produkSaya->count();
        $produkMenunggu = (clone $produkSaya)->where('status_verifikasi', 'menunggu')->count();
        $produkDisetujui = (clone $produkSaya)->where('status_verifikasi', 'disetujui')->count();
        
        // Hitung pesanan masuk yang belum selesai
        $pesananMasuk = Order::where('id_penjual', $user->id_user)
            ->whereNotIn('status_pesanan', ['selesai', 'ditolak', 'dibatalkan'])
            ->count();
        
        return view('dashboard.mahasiswa', compact('totalProduk', 'produkMenunggu', 'produkDisetujui', 'pesananMasuk'));
    }

    public function pembeli()
    {
        /** @var \App\Models\User $user */ // <-- TAMBAHKAN JUGA DI SINI
        $user = Auth::user();
        
        // Ambil pesanan milik pembeli beserta detail itemnya
        $pesananSaya = Order::with(['items', 'seller'])
            ->where('id_pembeli', $user->id_user)
            ->latest()
            ->get();
        
        return view('dashboard.pembeli', compact('pesananSaya'));
    }
}