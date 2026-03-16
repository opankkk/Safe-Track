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
    Route::get('/accident', \App\Livewire\Public\ReportAccident::class)->name('accident');
    Route::get('/unsafe', \App\Livewire\Public\ReportUnsafe::class)->name('unsafe');
});

/*
|--------------------------------------------------------------------------
| HSE MODULE (frontend dev)
|--------------------------------------------------------------------------
*/
Route::prefix('hse')->name('hse.')->group(function () {
    Route::get('/dashboard',  \App\Livewire\HSE\Dashboard::class)->name('dashboard');
    Route::get('/accident',   \App\Livewire\HSE\AccidentReport::class)->name('accident');
    Route::get('/incident',   \App\Livewire\HSE\IncidentReport::class)->name('incident');
    Route::get('/report',     \App\Livewire\HSE\ReportPage::class)->name('report');
});


/*
|--------------------------------------------------------------------------
| PIC MODULE (frontend dev)
|--------------------------------------------------------------------------
*/
Route::prefix('pic')->name('pic.')->group(function () {
    Route::get('/dashboard',  \App\Livewire\PIC\Dashboard::class)->name('dashboard');
    Route::get('/accident',   \App\Livewire\PIC\AccidentReport::class)->name('accident');
    Route::get('/incident',   \App\Livewire\PIC\IncidentReport::class)->name('incident');
});

/*
|--------------------------------------------------------------------------
| HSE MANAGER (frontend dev)
|--------------------------------------------------------------------------
*/
Route::prefix('hse-manager')->name('hse.manager.')->group(function () {
    Route::get('/dashboard',  \App\Livewire\HSEManager\Dashboard::class)->name('dashboard');
    Route::get('/accident',   \App\Livewire\HSEManager\AccidentReport::class)->name('accident');
    Route::get('/incident',   \App\Livewire\HSEManager\IncidentReport::class)->name('incident');
    Route::get('/report',               \App\Livewire\HSEManager\Report::class)->name('report');
    Route::get('/plan-tindak-lanjut',   \App\Livewire\HSEManager\Plan::class)->name('plan-tindak-lanjut');
});

Route::post('/logout', function () {
    Auth::logout();
    
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect('/auth/login'); 
})->name('logout');