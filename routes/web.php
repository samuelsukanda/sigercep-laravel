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
    Route::middleware(['auth', 'permission:komplain_ipsrs,read'])
        ->resource('komplain/ipsrs', KomplainIpsrsController::class)
        ->names('komplain.ipsrs');

    Route::middleware(['auth', 'permission:kesehatan_lingkungan,read'])
        ->resource('komplain/kesehatan-lingkungan', KesehatanLingkunganController::class)
        ->names('komplain.kesehatan-lingkungan');

    Route::middleware(['auth', 'permission:outsourcing_vendor,read'])
        ->resource('komplain/outsourcing-vendor', KomplainOutsourcingVendorController::class)
        ->names('komplain.outsourcing-vendor');

    // Reservasi
    Route::middleware(['auth', 'permission:reservasi_ruangan,read'])
        ->resource('reservasi/ruangan', ReservasiRuanganController::class)
        ->names('reservasi.ruangan');

    Route::middleware(['auth', 'permission:reservasi_kendaraan,read'])
        ->resource('reservasi/kendaraan', ReservasiKendaraanController::class)
        ->names('reservasi.kendaraan');

    // Visitasi
    Route::middleware(['auth', 'permission:visitasi,read'])
        ->resource('visitasi', VisitasiController::class)
        ->names('visitasi');

    // Komite Mutu
    Route::middleware(['auth', 'permission:mutu,read'])
        ->resource('komite-mutu/mutu', MutuController::class)
        ->names('komite-mutu.mutu');

    Route::middleware(['auth', 'permission:bank_spo,read'])
        ->resource('komite-mutu/bank-spo', BankSpoController::class)
        ->names('komite-mutu.bank-spo');

    Route::middleware(['auth', 'permission:manajemen_risiko,read'])
        ->resource('komite-mutu/manajemen-risiko', ManajemenRisikoController::class)
        ->names('komite-mutu.manajemen-risiko');
    Route::get('/bank-spo/file/{id}', [BankSpoController::class, 'showFile'])->name('bank-spo.show-file');

    // Desain Grafis
    Route::middleware(['auth', 'permission:desain_grafis,read'])
        ->resource('desain-grafis', DesainGrafisController::class)
        ->names('desain-grafis');

    // Kecelakaan Kerja
    Route::middleware(['auth', 'permission:kecelakaan_kerja,read'])
        ->resource('kecelakaan-kerja', KecelakaanKerjaController::class)
        ->names('kecelakaan-kerja');

    // Pengadaan Aset
    Route::middleware(['auth', 'permission:pengadaan_aset,read'])
        ->resource('pengadaan-aset/pengembalian-aset', PengembalianAsetController::class)
        ->names('pengadaan-aset.pengembalian-aset');

    Route::resource('pengadaan-aset/peminjaman-aset', PeminjamanAsetController::class)
        ->names('pengadaan-aset.peminjaman-aset');

    Route::resource('pengadaan-aset/pemindahan-aset', PemindahannAsetController::class)
        ->names('pengadaan-aset.pemindahan-aset');

    Route::resource('pengadaan-aset/laporan-aset-rusak', LaporanAsetRusakController::class)
        ->names('pengadaan-aset.laporan-aset-rusak');

    // Peminjaman
    Route::middleware(['auth', 'permission:peminjaman,read'])
        ->resource('peminjaman', PeminjamanController::class)
        ->names('peminjaman');

    // Kesiapan Ambulance
    Route::middleware(['auth', 'permission:kesiapan_ambulance,read'])
        ->resource('kesiapan-ambulance', KesiapanAmbulanceController::class)
        ->names('kesiapan-ambulance');

    // SDM & Hukum
    Route::get('/sdm-hukum/struktur-organisasi', [StrukturOrganisasiController::class, 'index'])
        ->name('sdm-hukum.struktur-organisasi.index');

    Route::middleware(['auth', 'permission:utw,read'])
        ->resource('sdm-hukum/utw', UtwController::class)
        ->names('sdm-hukum.utw');
    Route::get('/utw/file/{id}', [UtwController::class, 'showFile'])->name('utw.show-file');

    Route::middleware(['auth', 'permission:peraturan_perusahaan,read'])
        ->resource('sdm-hukum/peraturan-perusahaan', PeraturanPerusahaanController::class)
        ->names('sdm-hukum.peraturan-perusahaan');
    Route::get('/peraturan-perusahaan/file/{id}', [PeraturanPerusahaanController::class, 'showFile'])->name('peraturan-perusahaan.show-file');

    Route::middleware(['auth', 'permission:mandatory_training,read'])
        ->resource('sdm-hukum/mandatory-training', MandatoryTrainingController::class)
        ->names('sdm-hukum.mandatory-training');
    Route::get('/mandatory-training/file/{id}', [MandatoryTrainingController::class, 'showFile'])->name('mandatory-training.show-file');

    Route::middleware(['auth', 'permission:surat_keputusan,read'])
        ->resource('sdm-hukum/surat-keputusan', SuratKeputusanController::class)
        ->names('sdm-hukum.surat-keputusan');
    Route::get('/surat-keputusan/file/{id}', [SuratKeputusanController::class, 'showFile'])->name('surat-keputusan.show-file');

    // Komite Medik
    Route::middleware(['auth', 'permission:komite_medik,read'])
        ->resource('komite-medik', KomiteMedikController::class)
        ->names('komite-medik');
    Route::get('/komite-medik/file/{id}', [KomiteMedikController::class, 'showFile'])
        ->middleware(['auth', 'permission:komite_medik,read'])
        ->name('komite-medik.show-file');

    // Toner
    Route::middleware(['auth', 'permission:toner,read'])
        ->resource('toner', TonerController::class)
        ->names('toner');

    // Hardware
    Route::middleware(['auth', 'permission:hardware,read'])
        ->resource('hardware', HardwareController::class)
        ->names('hardware');
});
