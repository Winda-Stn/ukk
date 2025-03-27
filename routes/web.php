<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanStokController;

// Halaman awal langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login & Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect setelah login berdasarkan role
Route::get('/dashboard', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('kasir.dashboard');
    }
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');

// Middleware Admin - Akses Full
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('layout.dashboardA');
    })->name('admin.dashboard');

    // Barang
    Route::resource('/barang', BarangController::class)->names([
        'index'   => 'crud.barang.index',
        'create'  => 'crud.barang.create',
        'store'   => 'crud.barang.store',
        'edit'    => 'crud.barang.edit',
        'update'  => 'crud.barang.update',
        'destroy' => 'crud.barang.destroy',
    ]);

    // Kategori
    Route::resource('/kategori', KategoriController::class)->names([
        'index'   => 'crud.kategori.index',
        'create'  => 'crud.kategori.create',
        'store'   => 'crud.kategori.store',
        'edit'    => 'crud.kategori.edit',
        'update'  => 'crud.kategori.update',
        'destroy' => 'crud.kategori.destroy',
    ]);
});

// Middleware Admin & Kasir - Akses Pelanggan dan Transaksi
Route::middleware(['auth', 'admin_or_kasir'])->group(function () {
    Route::resource('/pelanggan', UserController::class)->names([
        'index'   => 'crud.pelanggan.index',
        'create'  => 'crud.pelanggan.create',
        'store'   => 'crud.pelanggan.store',
        'edit'    => 'crud.pelanggan.edit',
        'update'  => 'crud.pelanggan.update',
        'destroy' => 'crud.pelanggan.destroy',
    ]);

    // Transaksi Penjualan
    Route::prefix('/transaksi')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/store', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/edit/{id}', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('/update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/delete/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    
    });
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::get('/laporann', [LaporanStokController::class, 'index'])->name('laporann.index');
    Route::get('/laporann/export', [LaporanStokController::class, 'export'])->name('laporann.export');
});

// Middleware Kasir - Hanya Akses Dashboard Kasir & Tambah/Edit Pelanggan
Route::middleware(['auth', 'kasir'])->group(function () {
    Route::get('/kasir/dashboard', function () {
        return view('layout.dashboardK');
    })->name('kasir.dashboard');
});
