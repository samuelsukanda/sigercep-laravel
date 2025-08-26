<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesainGrafisController;
use App\Http\Controllers\KomplainIpsrsController;
use App\Http\Controllers\KomplainOutsourcingVendorController;
use App\Http\Controllers\ReservasiRuanganController;
use App\Http\Controllers\ReservasiKendaraanController;
use App\Http\Controllers\VisitasiController;
use App\Http\Controllers\MutuController;
use App\Http\Controllers\BankSpoController;
use App\Http\Controllers\UtwController;
use App\Http\Controllers\ManajemenRisikoController;
use App\Http\Controllers\KecelakaanKerjaController;
use App\Http\Controllers\PengembalianAsetController;
use App\Http\Controllers\PeminjamanAsetController;
use App\Http\Controllers\PemindahannAsetController;
use App\Http\Controllers\LaporanAsetRusakController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KesiapanAmbulanceController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Http\Controllers\KesehatanLingkunganController;
use App\Http\Controllers\TonerController;
use App\Http\Controllers\PeraturanPerusahaanController;
use App\Http\Controllers\MandatoryTrainingController;
use App\Http\Controllers\SuratKeputusanController;
use App\Http\Controllers\KomiteMedikController;
use App\Http\Controllers\HardwareController;

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

    // Roles
    Route::resource('roles', RoleController::class)->names('roles');

    // Pages - Views

    // Komplain
    Route::resource('komplain/ipsrs', KomplainIpsrsController::class)->names('komplain.ipsrs');
    Route::resource('komplain/kesehatan-lingkungan', KesehatanLingkunganController::class)
        ->names('komplain.kesehatan-lingkungan');
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

    // Komite Mutu
    Route::resource('komite-mutu/mutu', MutuController::class)
        ->names('komite-mutu.mutu');
    Route::resource('komite-mutu/bank-spo', BankSpoController::class)
        ->names('komite-mutu.bank-spo');
    Route::get('/bank-spo/file/{id}', [BankSpoController::class, 'showFile'])->name('bank-spo.show-file');
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

    // SDM & Hukum
    Route::get('/sdm-hukum/struktur-organisasi', [StrukturOrganisasiController::class, 'index'])
        ->name('sdm-hukum.struktur-organisasi.index');
    Route::resource('sdm-hukum/utw', UtwController::class)
        ->names('sdm-hukum.utw');
    Route::get('/utw/file/{id}', [UtwController::class, 'showFile'])->name('utw.show-file');
    Route::resource('sdm-hukum/peraturan-perusahaan', PeraturanPerusahaanController::class)
        ->names('sdm-hukum.peraturan-perusahaan');
    Route::get('/peraturan-perusahaan/file/{id}', [PeraturanPerusahaanController::class, 'showFile'])->name('peraturan-perusahaan.show-file');
    Route::resource('sdm-hukum/mandatory-training', MandatoryTrainingController::class)
        ->names('sdm-hukum.mandatory-training');
    Route::get('/mandatory-training/file/{id}', [MandatoryTrainingController::class, 'showFile'])->name('mandatory-training.show-file');
    Route::resource('sdm-hukum/surat-keputusan', SuratKeputusanController::class)
        ->names('sdm-hukum.surat-keputusan');
    Route::get('/surat-keputusan/file/{id}', [SuratKeputusanController::class, 'showFile'])->name('surat-keputusan.show-file');

    // Komite Medik
    Route::resource('komite-medik', KomiteMedikController::class)
        ->names('komite-medik');
    Route::get('/komite-medik/file/{id}', [KomiteMedikController::class, 'showFile'])->name('komite-medik.show-file');

    // Toner
    Route::resource('toner', TonerController::class)
        ->names('toner');

    // Hardware
    Route::resource('hardware', HardwareController::class)
        ->names('hardware');
});
