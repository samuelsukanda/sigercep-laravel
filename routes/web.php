
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DesainGrafisController;
use App\Http\Controllers\KomplainIpsrsController;
use App\Http\Controllers\KomplainOutsourcingVendorController;
use App\Http\Controllers\ReservasiRuanganController;
use App\Http\Controllers\ReservasiKendaraanController;
use App\Http\Controllers\VisitasiController;
use App\Http\Controllers\MutuController;
use App\Http\Controllers\PelaporanIkpController;
use App\Http\Controllers\PengajuanDokumenController;
use App\Http\Controllers\BankSpoController;
use App\Http\Controllers\BankIlmuController;
use App\Http\Controllers\DokumenITController;
use App\Http\Controllers\LaporanPerilakuController;
use App\Http\Controllers\UtwController;
use App\Http\Controllers\ManajemenRisikoController;
use App\Http\Controllers\KecelakaanKerjaController;
use App\Http\Controllers\PengembalianAsetController;
use App\Http\Controllers\PeminjamanAsetController;
use App\Http\Controllers\PemindahanAsetController;
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
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\IndicatorValueController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware();
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/layouts/app', function () {
        return view('app');
    })->name('app');

    // Permission Management Routes
    Route::middleware(['auth'])
        ->prefix('permissions')
        ->name('permissions.')
        ->group(function () {
            Route::put('/update-rule/{rule}', [PermissionController::class, 'updateRule'])->name('updateRule');
            Route::delete('/delete-rule/{rule}', [PermissionController::class, 'deleteRule'])
                ->name('deleteRule');
            Route::get('/', [PermissionController::class, 'index'])
                ->name('index')
                ->middleware('permission:permissions,read');
            Route::post('/', [PermissionController::class, 'store'])
                ->name('store')
                ->middleware('permission:permissions,create');
            Route::delete('/{permission}', [PermissionController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:permissions,delete');
            Route::post('/{permission}/add-rule', [PermissionController::class, 'addRule'])
                ->name('add-rule')
                ->middleware('permission:permissions,create');
        });

    // User
    Route::middleware(['auth'])->group(function () {

        // User Monitoring
        Route::get('/user-monitoring', [UserSessionController::class, 'index'])
            ->name('user.monitoring');

        Route::get('/user-monitoring-data', [UserSessionController::class, 'data'])
            ->middleware('auth')
            ->name('user.monitoring.data');

        // List User
        Route::get('/users', [UserController::class, 'index'])
            ->name('users');
    });

    // Pages - Views

    // Ticket
    // User routes
    Route::middleware(['auth', 'permission:helpdesk,create'])
        ->resource('helpdesk', TicketController::class)
        ->only(['create', 'store', 'index', 'show']);

    // Admin routes
    Route::middleware(['auth', 'permission:helpdesk,manage'])->prefix('admin')->name('admin.')->group(function () {
        // Manajemen tiket untuk superadmin
        Route::resource('helpdesk', AdminTicketController::class)
            ->except(['create', 'store']); // hanya index, show, edit, update, destroy
        // Approval dan update status
        Route::put('helpdesk/{ticket}/approve', [AdminTicketController::class, 'approve'])->name('helpdesk.approve');
        Route::put('helpdesk/{ticket}/update-status', [AdminTicketController::class, 'updateStatus'])->name('helpdesk.update-status');
    });

    // Report routes
    Route::middleware(['auth', 'permission:helpdesk,manage'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('summary', [ReportController::class, 'summary'])->name('summary');
        Route::get('export', [ReportController::class, 'export'])->name('export');
    });

    // Notifikasi
    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::get('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::get('/notifications/{id}/go', [NotificationController::class, 'go'])->name('notifications.go');
    });

    // Bank Ilmu
    Route::middleware(['auth'])
        ->resource('bank-ilmu', BankIlmuController::class)
        ->names('bank-ilmu');
    Route::get('/bank-ilmu/file/{id}', [BankIlmuController::class, 'showFile'])->name('bank-ilmu.show-file');

    // Dokumen IT
    Route::middleware(['auth'])
        ->resource('dokumen-it', DokumenITController::class)
        ->names('dokumen-it');
    Route::get('/dokumen-it/file/{id}', [DokumenITController::class, 'showFile'])->name('dokumen-it.show-file');

    // Komplain
    Route::middleware(['auth'])
        ->resource('komplain/ipsrs', KomplainIpsrsController::class)
        ->names('komplain.ipsrs');

    Route::middleware(['auth'])
        ->resource('komplain/kesehatan-lingkungan', KesehatanLingkunganController::class)
        ->names('komplain.kesehatan-lingkungan');

    Route::middleware(['auth'])
        ->resource('komplain/outsourcing-vendor', KomplainOutsourcingVendorController::class)
        ->names('komplain.outsourcing-vendor');

    // Reservasi
    Route::middleware(['auth'])
        ->resource('reservasi/ruangan', ReservasiRuanganController::class)
        ->names('reservasi.ruangan');

    Route::middleware(['auth'])
        ->resource('reservasi/kendaraan', ReservasiKendaraanController::class)
        ->names('reservasi.kendaraan');

    // Visitasi
    Route::middleware(['auth'])
        ->resource('visitasi', VisitasiController::class)
        ->names('visitasi');

    // Komite Mutu
    Route::middleware(['auth'])
        ->resource('komite-mutu/mutu', MutuController::class)
        ->names('komite-mutu.mutu');

    Route::middleware(['auth'])
        ->resource('komite-mutu/pelaporan-ikp', PelaporanIkpController::class)
        ->names('komite-mutu.pelaporan-ikp');

    Route::middleware(['auth'])
        ->resource('komite-mutu/pengajuan-dokumen', PengajuanDokumenController::class)
        ->names('komite-mutu.pengajuan-dokumen');
    Route::get('/pengajuan-dokumen/file/{id}', [PengajuanDokumenController::class, 'showFile'])->name('pengajuan-dokumen.show-file');

    Route::middleware(['auth'])->group(function () {
        Route::get('/komite-mutu/bank-spo/file/{id}', [BankSpoController::class, 'showFile'])
            ->name('bank-spo.show-file');
        Route::resource('komite-mutu/bank-spo', BankSpoController::class)
            ->names('komite-mutu.bank-spo');
    });

    Route::middleware(['auth'])
        ->resource('komite-mutu/laporan-perilaku', LaporanPerilakuController::class)
        ->names('komite-mutu.laporan-perilaku');
    Route::get('/laporan-perilaku/file/{id}', [LaporanPerilakuController::class, 'showFile'])->name('laporan-perilaku.show-file');

    Route::middleware(['auth'])
        ->resource('komite-mutu/manajemen-risiko', ManajemenRisikoController::class)
        ->names('komite-mutu.manajemen-risiko');

    // Desain Grafis
    Route::middleware(['auth'])
        ->resource('desain-grafis', DesainGrafisController::class)
        ->names('desain-grafis');

    // Kecelakaan Kerja
    Route::middleware(['auth'])
        ->resource('kecelakaan-kerja', KecelakaanKerjaController::class)
        ->names('kecelakaan-kerja');

    // Pengadaan Aset
    Route::middleware(['auth'])
        ->resource('pengadaan-aset/pengembalian-aset', PengembalianAsetController::class)
        ->names('pengadaan-aset.pengembalian-aset');

    Route::middleware(['auth'])
        ->resource('pengadaan-aset/peminjaman-aset', PeminjamanAsetController::class)
        ->names('pengadaan-aset.peminjaman-aset');

    Route::middleware(['auth'])
        ->resource('pengadaan-aset/pemindahan-aset', PemindahanAsetController::class)
        ->names('pengadaan-aset.pemindahan-aset');

    Route::middleware(['auth'])
        ->resource('pengadaan-aset/laporan-aset-rusak', LaporanAsetRusakController::class)
        ->names('pengadaan-aset.laporan-aset-rusak');

    // Peminjaman
    Route::middleware(['auth'])
        ->resource('peminjaman', PeminjamanController::class)
        ->names('peminjaman');

    // Kesiapan Ambulance
    Route::middleware(['auth'])
        ->resource('kesiapan-ambulance', KesiapanAmbulanceController::class)
        ->names('kesiapan-ambulance');

    // SDM & Hukum
    Route::get('/sdm-hukum/struktur-organisasi', [StrukturOrganisasiController::class, 'index'])
        ->name('sdm-hukum.struktur-organisasi.index');

    Route::middleware(['auth'])
        ->resource('sdm-hukum/utw', UtwController::class)
        ->names('sdm-hukum.utw');
    Route::get('/utw/file/{id}', [UtwController::class, 'showFile'])->name('utw.show-file');

    Route::middleware(['auth'])
        ->resource('sdm-hukum/peraturan-perusahaan', PeraturanPerusahaanController::class)
        ->names('sdm-hukum.peraturan-perusahaan');
    Route::get('/peraturan-perusahaan/file/{id}', [PeraturanPerusahaanController::class, 'showFile'])->name('peraturan-perusahaan.show-file');

    Route::middleware(['auth'])
        ->resource('sdm-hukum/mandatory-training', MandatoryTrainingController::class)
        ->names('sdm-hukum.mandatory-training');
    Route::get('/mandatory-training/file/{id}', [MandatoryTrainingController::class, 'showFile'])->name('mandatory-training.show-file');

    Route::middleware(['auth'])
        ->resource('sdm-hukum/surat-keputusan', SuratKeputusanController::class)
        ->names('sdm-hukum.surat-keputusan');
    Route::get('/surat-keputusan/file/{id}', [SuratKeputusanController::class, 'showFile'])->name('surat-keputusan.show-file');

    // Komite Medik
    Route::middleware(['auth'])
        ->resource('komite-medik', KomiteMedikController::class)
        ->names('komite-medik');
    Route::get('/komite-medik/file/{id}', [KomiteMedikController::class, 'showFile'])
        ->name('komite-medik.show-file');

    // Toner
    Route::middleware(['auth'])
        ->resource('toner', TonerController::class)
        ->names('toner');

    // Hardware
    Route::middleware(['auth'])->group(function () {
        Route::get('hardware/reports', [HardwareController::class, 'report'])->name('hardware.reports');
        Route::get('hardware/reports/minipc', [HardwareController::class, 'reportMiniPc'])->name('hardware.reports.minipc');
        Route::get('hardware/{ip}/device-printer', [HardwareController::class, 'showDevicePrinter'])->name('hardware.device-printer.show');
        Route::post('hardware/{ip}/device-printer', [HardwareController::class, 'storeDevicePrinter'])->name('hardware.device-printer.store');
        Route::delete('hardware/device-printer/{id}', [HardwareController::class, 'destroyDevicePrinter'])->name('hardware.device-printer.destroy');
        Route::resource('hardware', HardwareController::class)->names('hardware');
    });

    // Indikator Mutu
    Route::middleware(['auth'])->group(function () {
        Route::get('indicators', [IndicatorController::class, 'index'])
            ->middleware('permission:mutu,read')
            ->name('indicators.index');
        Route::post('indicators', [IndicatorController::class, 'store'])
            ->middleware('permission:mutu,create')
            ->name('indicators.store');
    });

    Route::middleware(['auth'])->prefix('indicator-values')->name('indicator-values.')->group(function () {
        Route::get('bulk-edit', [IndicatorValueController::class, 'editBulk'])
            ->middleware('permission:mutu,read')
            ->name('bulk-edit');
        Route::post('bulk-update', [IndicatorValueController::class, 'updateBulk'])
            ->middleware('permission:mutu,update')
            ->name('bulk-update');
    });
});
