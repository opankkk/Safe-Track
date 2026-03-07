<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('user.login');
});
/*
|--------------------------------------------------------------------------
| AUTH (Frontend Only)
|--------------------------------------------------------------------------
*/
Route::get('/auth/login', \App\Livewire\Auth\Login::class)->name('user.login');
Route::get('/auth/forgotpw', \App\Livewire\Auth\Forgotpw::class)->name('user.forgotpw');
Route::get('/auth/resetpw/{token}', \App\Livewire\Auth\Resetpw::class)->name('password.reset');

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
    Route::view('/report', 'livewire.h-s-e.report')->name('report');
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
    Route::view('/dashboard', 'livewire.h-s-e-manager.dashboard')
        ->name('dashboard');
    Route::view('/accident', 'livewire.h-s-e-manager.accident-report')
        ->name('accident');
    Route::view('/incident', 'livewire.h-s-e-manager.incident-report')
        ->name('incident');
    Route::view('/report', 'livewire.h-s-e-manager.report')
        ->name('report');
    Route::view('/plan-tindak-lanjut', 'livewire.h-s-e-manager.plan')
        ->name('plan-tindak-lanjut');
});

Route::post('/logout', function () {
    Auth::logout();
    
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect('/auth/login'); // ← Ganti dari /login ke /auth/login
})->name('logout');