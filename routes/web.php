<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesainGrafisController;
use App\Http\Controllers\KomplainIpsrsController;
use App\Http\Controllers\KomplainOutsourcingVendorController;
use App\Http\Controllers\ReservasiRuanganController;
use App\Http\Controllers\ReservasiKendaraanController;
use App\Http\Controllers\VisitasiController;
use App\Http\Controllers\MutuController;
use App\Http\Controllers\ManajemenRisikoController;
use App\Http\Controllers\KecelakaanKerjaController;
use App\Http\Controllers\PengembalianAsetController;
use App\Http\Controllers\PeminjamanAsetController;
use App\Http\Controllers\PemindahannAsetController;
use App\Http\Controllers\LaporanAsetRusakController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KesiapanAmbulanceController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/layouts/app', function () {
        return view('app');
    })->name('app');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // Pages - Views

    // Komplain
    Route::resource('komplain/ipsrs', KomplainIpsrsController::class)->names('komplain.ipsrs');
    Route::resource('komplain/outsourcing-vendor', KomplainOutsourcingVendorController::class)
        ->names('komplain.outsourcing-vendor');

    // Reservasi
    Route::resource('reservasi/ruangan', ReservasiRuanganController::class)
        ->names('reservasi.ruangan');
    Route::resource('reservasi/kendaraan', ReservasiKendaraanController::class)
        ->names('reservasi.kendaraan');

    // Visitasi
    Route::resource('visitasi', VisitasiController::class)
        ->names('visitasi');

    // Mutu
    Route::resource('komite-mutu/mutu', MutuController::class)
        ->names('komite-mutu.mutu');
    Route::resource('komite-mutu/manajemen-risiko', ManajemenRisikoController::class)
        ->names('komite-mutu.manajemen-risiko');

    // Desain Grafis
    Route::resource('desain-grafis', DesainGrafisController::class)
        ->names('desain-grafis');

    // Kecelakaan Kerja
    Route::resource('kecelakaan-kerja', KecelakaanKerjaController::class)
        ->names('kecelakaan-kerja');

    // Pengadaan Aset
    Route::resource('pengadaan-aset/pengembalian-aset', PengembalianAsetController::class)
        ->names('pengadaan-aset.pengembalian-aset');
    Route::resource('pengadaan-aset/peminjaman-aset', PeminjamanAsetController::class)
        ->names('pengadaan-aset.peminjaman-aset');
    Route::resource('pengadaan-aset/pemindahan-aset', PemindahannAsetController::class)
        ->names('pengadaan-aset.pemindahan-aset');
    Route::resource('pengadaan-aset/laporan-aset-rusak', LaporanAsetRusakController::class)
        ->names('pengadaan-aset.laporan-aset-rusak');

    // Peminjaman
    Route::resource('peminjaman', PeminjamanController::class)
        ->names('peminjaman');

    // Kesiapan Ambulance
    Route::resource('kesiapan-ambulance', KesiapanAmbulanceController::class)
        ->names('kesiapan-ambulance');
});
