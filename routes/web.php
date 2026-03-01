<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| AUTH (Frontend Only)
|--------------------------------------------------------------------------
*/
Route::view('/auth/login', 'livewire.auth.login')->name('user.login');
Route::view('/auth/forgotpw', 'livewire.auth.forgotpw')->name('user.forgotpw');

/*
|--------------------------------------------------------------------------
| PUBLIC (Pelapor tanpa login)
|--------------------------------------------------------------------------
*/
Route::prefix('public/report')->name('public.report.')->group(function () {
    Route::view('/accident', 'livewire.public.report-accident')->name('accident');
    Route::view('/unsafe',   'livewire.public.report-unsafe')->name('unsafe');
});

/*
|--------------------------------------------------------------------------
| HSE MODULE (frontend dev)
|--------------------------------------------------------------------------
*/
Route::prefix('hse')->name('hse.')->group(function () {
    Route::view('/dashboard', 'livewire.h-s-e.dashboard')->name('dashboard');
    Route::view('/accident',  'livewire.h-s-e.accident-report')->name('accident');
    Route::view('/incident',  'livewire.h-s-e.incident-report')->name('incident');
    Route::view('/perbaikan', 'livewire.h-s-e.perbaikan-report')->name('perbaikan');
});

/*
|--------------------------------------------------------------------------
| PIC MODULE (frontend dev)
|--------------------------------------------------------------------------
*/
Route::prefix('pic')->name('pic.')->group(function () {
    Route::view('/dashboard', 'livewire.p-i-c.dashboard')->name('dashboard');
    Route::view('/accident',  'livewire.p-i-c.accident-report')->name('accident');
    Route::view('/incident',  'livewire.p-i-c.incident-report')->name('incident');
});

/*
|--------------------------------------------------------------------------
| HSE MANAGER (frontend dev)
|--------------------------------------------------------------------------
*/
Route::prefix('hse-manager')->name('hse.manager.')->group(function () {
    Route::view('/dashboard', 'livewire.h-s-e-manager.dashboard')->name('dashboard');

    // kalau manager belum ada halaman accident/incident, boleh dihapus dulu
    Route::view('/accident', 'livewire.h-s-e-manager.accident-report')->name('accident');
    Route::view('/incident', 'livewire.h-s-e-manager.incident-report')->name('incident');
});