<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user mahasiswa
        $sari = User::where('email', 'sari@mahasiswa.test')->first();
        $dito = User::where('email', 'dito@mahasiswa.test')->first();
        
        // Ambil kategori
        $kerajinan = Category::where('nama_kategori', 'Kerajinan')->first();
        $alat = Category::where('nama_kategori', 'Alat Sederhana')->first();
        $prototipe = Category::where('nama_kategori', 'Prototipe')->first();
        $media = Category::where('nama_kategori', 'Media Pembelajaran')->first();
        $kreatif = Category::where('nama_kategori', 'Produk Kreatif')->first();

        // Daftar 20 Produk Dummy
        $dummyProducts = [
            // --- Sari Wulandari ---
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $alat->id_kategori,
                'nama_produk' => 'Alat Penyiram Tanaman Otomatis', 'deskripsi' => 'Penyiram tanaman berbasis botol dengan timer sederhana.',
                'harga' => 75000, 'stok' => 5, 'gambar' => 'https://images.unsplash.com/photo-1589923188900-85dae523342b?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $kerajinan->id_kategori,
                'nama_produk' => 'Miniatur Rumah Kayu', 'deskripsi' => 'Miniatur rumah dari kayu bekas, cocok untuk pajangan.',
                'harga' => 50000, 'stok' => 8, 'gambar' => 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $kreatif->id_kategori,
                'nama_produk' => 'Gantungan Kunci Resin', 'deskripsi' => 'Gantungan kunci resin warna-warni.',
                'harga' => 15000, 'stok' => 20, 'gambar' => 'https://images.unsplash.com/photo-1584916201218-f4242ceb4809?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'ditolak', 'catatan_admin' => 'Foto produk belum jelas, mohon unggah ulang.'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $kerajinan->id_kategori,
                'nama_produk' => 'Tas Rajut Handmade', 'deskripsi' => 'Tas rajut tangan dari benang katun premium.',
                'harga' => 45000, 'stok' => 10, 'gambar' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $kreatif->id_kategori,
                'nama_produk' => 'Tempat Pensil Unik', 'deskripsi' => 'Tempat pensil dari botol plastik daur ulang.',
                'harga' => 20000, 'stok' => 15, 'gambar' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $kerajinan->id_kategori,
                'nama_produk' => 'Vas Bunga Keramik', 'deskripsi' => 'Vas bunga keramik buatan tangan, motif minimalis.',
                'harga' => 65000, 'stok' => 4, 'gambar' => 'https://images.unsplash.com/photo-1581783342308-f792dbdd27c5?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $alat->id_kategori,
                'nama_produk' => 'Rak Buku Lipat', 'deskripsi' => 'Rak buku portabel yang bisa dilipat, hemat ruang.',
                'harga' => 95000, 'stok' => 3, 'gambar' => 'https://down-id.img.susercontent.com/file/id-11134207-7rasj-m26efuuyhy0358',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $media->id_kategori,
                'nama_produk' => 'Alat Peraga Sistem Pencernaan', 'deskripsi' => 'Alat peraga biologi sistem pencernaan manusia.',
                'harga' => 130000, 'stok' => 2, 'gambar' => 'https://images.unsplash.com/photo-1530210124550-912dc1381cb8?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $kreatif->id_kategori,
                'nama_produk' => 'Lampu Hias dari Sendok', 'deskripsi' => 'Lampu hias unik dari bahan sendok plastik bekas.',
                'harga' => 40000, 'stok' => 7, 'gambar' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'menunggu'
            ],
            [
                'id_penjual' => $sari->id_user, 'id_kategori' => $kerajinan->id_kategori,
                'nama_produk' => 'Tempat Tisu Anyaman', 'deskripsi' => 'Tempat tisu dari anyaman bambu alami.',
                'harga' => 30000, 'stok' => 12, 'gambar' => 'https://th.bing.com/th/id/OIP.9j-OnkjGeMwXmzHSXVW75AHaHa?w=202&h=202&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3',
                'status_verifikasi' => 'disetujui'
            ],

            // --- Dito Pratama ---
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $media->id_kategori,
                'nama_produk' => 'Alat Peraga Tata Surya', 'deskripsi' => 'Alat peraga model tata surya untuk media belajar.',
                'harga' => 90000, 'stok' => 3, 'gambar' => 'https://images.unsplash.com/photo-1614730321146-b6fa6a46bcb4?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $prototipe->id_kategori,
                'nama_produk' => 'Prototipe Lampu Tidur Sensor', 'deskripsi' => 'Prototipe lampu tidur dengan sensor cahaya.',
                'harga' => 120000, 'stok' => 2, 'gambar' => 'https://images.unsplash.com/photo-1550985616-10810253b84d?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'menunggu'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $prototipe->id_kategori,
                'nama_produk' => 'Robot Line Follower Sederhana', 'deskripsi' => 'Robot edukasi pengikut garis untuk pemula.',
                'harga' => 350000, 'stok' => 1, 'gambar' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $media->id_kategori,
                'nama_produk' => 'Poster Edukasi Tata Surya', 'deskripsi' => 'Poster cetak resolusi tinggi tentang tata surya.',
                'harga' => 25000, 'stok' => 50, 'gambar' => 'https://images.unsplash.com/photo-1543722530-d2c3201371e7?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $alat->id_kategori,
                'nama_produk' => 'Alat Pemotong Kertas Mini', 'deskripsi' => 'Alat pemotong kertas praktis untuk desain grafis.',
                'harga' => 85000, 'stok' => 6, 'gambar' => 'https://images.unsplash.com/photo-1587145820266-a5951ee6f620?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'menunggu'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $media->id_kategori,
                'nama_produk' => 'Model DNA', 'deskripsi' => 'Model struktur DNA 3D untuk praktikum biologi.',
                'harga' => 110000, 'stok' => 4, 'gambar' => 'https://images.unsplash.com/photo-1530026405186-ed1f139313f8?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $prototipe->id_kategori,
                'nama_produk' => 'Prototipe Alat Deteksi Banjir', 'deskripsi' => 'Sensor peringatan dini banjir berbasis Arduino.',
                'harga' => 250000, 'stok' => 2, 'gambar' => 'https://miro.medium.com/v2/resize:fit:397/1*bOZZF--Sg0GOKNztK7ogIQ.png',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $kerajinan->id_kategori,
                'nama_produk' => 'Maket Rumah Adat', 'deskripsi' => 'Maket rumah adat dari stik es krim, detail dan rapi.',
                'harga' => 150000, 'stok' => 2, 'gambar' => 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $prototipe->id_kategori,
                'nama_produk' => 'Prototipe Lengan Robotik', 'deskripsi' => 'Lengan robotik sederhana dengan kontrol joystick.',
                'harga' => 450000, 'stok' => 1, 'gambar' => 'https://tse3.mm.bing.net/th/id/OIP.olaOPYLlZhjjxDh_1VECuQHaHQ?r=0&rs=1&pid=ImgDetMain&o=7&rm=3',
                'status_verifikasi' => 'disetujui'
            ],
            [
                'id_penjual' => $dito->id_user, 'id_kategori' => $kreatif->id_kategori,
                'nama_produk' => 'Dompet Kulit Sintetis', 'deskripsi' => 'Dompet buatan tangan dari sisa bahan kulit sintetis.',
                'harga' => 35000, 'stok' => 8, 'gambar' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?q=80&w=600&auto=format&fit=crop',
                'status_verifikasi' => 'ditolak', 'catatan_admin' => 'Deskripsi produk kurang lengkap, tolong tambahkan dimensi.'
            ],
        ];

        // Looping untuk memasukkan 20 produk ke database
        foreach ($dummyProducts as $item) {
            Product::create([
                'id_penjual' => $item['id_penjual'],
                'id_kategori' => $item['id_kategori'],
                'nama_produk' => $item['nama_produk'],
                'deskripsi' => $item['deskripsi'],
                'harga' => $item['harga'],
                'stok' => $item['stok'],
                'gambar' => $item['gambar'],
                'status_penilaian' => 'sudah_dinilai',
                'konfirmasi_izin' => true,
                'status_verifikasi' => $item['status_verifikasi'],
                'catatan_admin' => $item['catatan_admin'] ?? null,
            ]);
        }
    }
}