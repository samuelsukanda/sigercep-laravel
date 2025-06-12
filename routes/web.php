<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KomplainIpsrsController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('komplain/ipsrs', \App\Http\Controllers\KomplainIpsrsController::class)->names('komplain.ipsrs');

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
});
