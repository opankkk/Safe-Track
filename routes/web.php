<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});



/*
|--------------------------------------------------------------------------
| AUTH (Frontend Only)
|--------------------------------------------------------------------------
*/

Route::view('/auth/login', 'livewire.auth.login')
    ->name('user.login');
Route::view('/auth/forgotpw', 'livewire.auth.forgotpw')
    ->name('user.forgotpw');
    



/*
|--------------------------------------------------------------------------
| PUBLIC (Pelapor tanpa login)
|--------------------------------------------------------------------------
*/

Route::view('/public/report/accident', 'livewire.public.report-accident')
    ->name('public.report.accident');

Route::view('/public/report/unsafe', 'livewire.public.report-unsafe')
    ->name('public.report.unsafe');



/*
|--------------------------------------------------------------------------
| HSE MODULE
|--------------------------------------------------------------------------
*/

Route::prefix('hse')->group(function () {

    // Dashboard
    Route::view('/dashboard', 'livewire.h-s-e.dashboard')
        ->name('hse.dashboard');

    // Approval - Laporan Kerusakan (Accident)
    Route::view('/accident', 'livewire.h-s-e.accident-report')
        ->name('hse.accident');

    // Approval - Laporan Temuan (Unsafe Action & Condition)
    Route::view('/incident', 'livewire.h-s-e.incident-report')
        ->name('hse.incident');

    // Laporan Perbaikan (Riwayat) - Placeholder dulu
    Route::view('/perbaikan', 'livewire.h-s-e.perbaikan-report')
        ->name('hse.perbaikan');
});



/*
|--------------------------------------------------------------------------
| HSE MANAGER
|--------------------------------------------------------------------------
*/

Route::view('/hse-manager/dashboard', 'livewire.h-s-e-manager.dashboard')
    ->name('hse.manager.dashboard');



/*
|--------------------------------------------------------------------------
| PIC
|--------------------------------------------------------------------------
*/

Route::view('/pic/dashboard', 'livewire.p-i-c.dashboard')
    ->name('pic.dashboard');