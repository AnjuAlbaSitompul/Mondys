<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('auth.login'));
Route::post('/signin', [AuthController::class, 'signIn']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
    Route::get('/barang', [BarangController::class, 'index'])->name('barang');
    Route::post('/barang', [BarangController::class, 'create']);

    Route::get('/boarding', function () {
        return view('pages.boarding');
    })->name('boarding');;

    Route::get('/users/picker', [UserController::class, 'getPicker']);
});
