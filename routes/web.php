<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MahasiswaProductController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\PembeliOrderController;
use App\Http\Controllers\MahasiswaOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartCheckoutController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\RegionController;

// Halaman publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [HomeController::class, 'catalog'])->name('catalog'); 
Route::get('/tentang', fn() => view('tentang'))->name('tentang');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.show');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
// Profil pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

// Dashboard sesuai role
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    
    // CRUD Kategori
    Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [AdminCategoryController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{category}', [AdminCategoryController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{category}', [AdminCategoryController::class, 'destroy'])->name('kategori.destroy');

    // Rute Verifikasi Produk
    Route::get('/produk', [AdminProductController::class, 'index'])->name('produk.index');
    
    // Gunakan ->parameters(['produk' => 'product']) agar tidak error 403/404 seperti sebelumnya
    Route::post('/produk/{product}/approve', [AdminProductController::class, 'approve'])->name('produk.approve');
    Route::post('/produk/{product}/reject', [AdminProductController::class, 'reject'])->name('produk.reject');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
    
    Route::delete('produk/bulk', [MahasiswaProductController::class, 'bulkDestroy'])->name('produk.bulkDestroy');
    Route::resource('produk', MahasiswaProductController::class)->parameters(['produk' => 'product']);
    
    // Tambahan: Rute Pesanan
    Route::get('/pesanan', [MahasiswaOrderController::class, 'index'])->name('pesanan.index');
    Route::put('/pesanan/{order}/status', [MahasiswaOrderController::class, 'updateStatus'])->name('pesanan.updateStatus');
});

// Tambahkan ->name('pembeli.') di sini
Route::middleware(['auth', 'role:pembeli'])->prefix('pembeli')->name('pembeli.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pembeli'])->name('dashboard');
    
    // Pembatalan pesanan oleh pembeli
Route::post('/pesanan/{order}/batal', [PembeliOrderController::class, 'cancel'])
    ->name('pesanan.cancel');

// Konfirmasi pesanan selesai oleh pembeli
Route::post('/pesanan/{order}/selesai', [PembeliOrderController::class, 'complete'])
    ->name('pesanan.complete');

    // Fitur Keranjang
    Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/{id}', [CartController::class, 'store'])->name('keranjang.store');
    Route::put('/keranjang/{id}', [CartController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('keranjang.destroy');
    // Fitur Checkout Keranjang
    Route::get('/checkout-keranjang', [CartCheckoutController::class, 'create'])
        ->name('checkout.keranjang.create');

    Route::post('/checkout-keranjang', [CartCheckoutController::class, 'store'])
        ->name('checkout.keranjang.store');
    // Dengan begini, nama route otomatis menjadi 'pembeli.checkout.create'
    Route::get('/checkout/{product}', [PembeliOrderController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{product}', [PembeliOrderController::class, 'store'])->name('checkout.store');
});

Route::get('/api/regions/kota', [RegionController::class, 'getKota']);
Route::get('/api/regions/kecamatan', [RegionController::class, 'getKecamatan']);
Route::get('/api/regions/desa', [RegionController::class, 'getDesa']);
