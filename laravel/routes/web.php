<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerAuth;
use App\Http\Controllers\ControllerDashboard;
use App\Http\Controllers\ControllerProduk;
use App\Http\Controllers\ControllerMember;
use App\Http\Controllers\ControllerPenjualan;
use App\Http\Controllers\ControllerLaporan;
use App\Http\Controllers\ControllerUser;

// Guest routes (login)
Route::get('/login', [ControllerAuth::class, 'showLogin'])->name('login');
Route::post('/login', [ControllerAuth::class, 'login']);
Route::post('/logout', [ControllerAuth::class, 'logout'])->name('logout');

// Semua route di bawah ini butuh login (auth)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [ControllerDashboard::class, 'index'])->name('dashboard');

    // Transaksi (kasir dan admin boleh akses)
    Route::prefix('transaksi')->group(function () {
        Route::get('/', [ControllerPenjualan::class, 'index'])->name('transaksi');
        Route::post('/scan', [ControllerPenjualan::class, 'scan'])->name('scan');
        Route::post('/update-cart', [ControllerPenjualan::class, 'updateCart'])->name('updateCart');
        Route::delete('/remove-cart/{id}', [ControllerPenjualan::class, 'removeCart'])->name('removeCart');
        Route::get('/cari-member', [ControllerPenjualan::class, 'cariMember'])->name('cariMember');
        Route::post('/bayar', [ControllerPenjualan::class, 'prosesBayar'])->name('prosesBayar');
        Route::get('/struk/{id}', [ControllerPenjualan::class, 'struk'])->name('struk');
    });

    // Rute yang hanya untuk ADMIN (middleware role:admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('produk', ControllerProduk::class)->names('produk');
        Route::resource('member', ControllerMember::class)->names('member');
        Route::resource('user', ControllerUser::class)->names('user');
        Route::get('/laporan', [ControllerLaporan::class, 'index'])->name('laporan');
        Route::get('/laporan/stok', [ControllerLaporan::class, 'stok'])->name('laporan.stok');
    });
});
