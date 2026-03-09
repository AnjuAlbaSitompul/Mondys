<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('auth.login'));
Route::post('/signin', [AuthController::class, 'signIn']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::prefix('picker')->name('picker.')->group(function () {

        Route::get('/pick', function () {
            return view('pages.pick');
        })->name('pick');;
    });
});
