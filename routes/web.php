<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view ('auth/login', 'livewire.auth.login')->name('user.login');
Route::view('/hse/dashboard', 'livewire.h-s-e.dashboard')->name('hse.dashboard');
Route::view('/public/report/accident', 'livewire.public.report-accident')->name('public.report.accident');
Route::view('/public/report/unsafe', 'livewire.public.report-unsafe')
    ->name('public.report.unsafe');