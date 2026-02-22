<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth (Front-end only)
Route::view('auth/login', 'livewire.auth.login')->name('user.login');

// Dashboard (HSE Manager)
Route::view('/hse-manager/dashboard', 'livewire.h-s-e-manager.dashboard')
    ->name('hse.manager.dashboard');

// Public (Pelapor tanpa login)
Route::view('/public/report/accident', 'livewire.public.report-accident')
    ->name('public.report.accident');

Route::view('/public/report/unsafe', 'livewire.public.report-unsafe')
    ->name('public.report.unsafe');