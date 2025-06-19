<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KomplainIpsrsController;
use App\Http\Controllers\KomplainOutsourcingVendorController;
use App\Http\Controllers\ReservasiRuanganController;
use App\Http\Controllers\ReservasiKendaraanController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/layouts/app', function () {
        return view('app');
    })->name('app');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // Pages
    Route::resource('komplain/ipsrs', KomplainIpsrsController::class)->names('komplain.ipsrs');
    Route::resource('komplain/outsourcing-vendor', KomplainOutsourcingVendorController::class)
        ->names('komplain.outsourcing-vendor');
    Route::resource('reservasi/ruangan', ReservasiRuanganController::class)
        ->names('reservasi.ruangan');
    Route::resource('reservasi/kendaraan',ReservasiKendaraanController::class)
        ->names('reservasi.kendaraan');
});
