<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BoardingListController;
use App\Http\Controllers\DeliveringController;
use App\Http\Controllers\LoadingContoroller;
use App\Http\Controllers\PickController;
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
    Route::delete('/barang/{id}', [BarangController::class, 'delete'])->name('barang.delete');
    Route::get('/barang/items', [BarangController::class, 'getAll']);
    Route::patch('/barang/{id}/update-picker', [BarangController::class, 'updatePicker'])->name('barang.update-picker');

    Route::get('/pick', [PickController::class, 'index'])->name('barang.pick');
    Route::get('/pick/items', [PickController::class, 'getAll'])->name('barang.pick');
    Route::patch('/pick/{id}/end', [PickController::class, 'endPick'])->name('pick.end');
    Route::patch('/pick/end', [PickController::class, 'end']);

    Route::get('/boarding', function () {
        return view('pages.boarding');
    })->name('boarding');
    Route::post('/boarding', [BoardingListController::class, 'store'])->name('boarding.store');
    Route::get('/boarding/items', [BoardingListController::class, 'getAll'])->name('boarding.items');

    Route::get('/titip', [DeliveringController::class, 'index'])->name('titip');
    Route::post('/titip', [DeliveringController::class, 'create'])->name('titip.create');
    Route::get('/titip/items', [DeliveringController::class, 'getAll'])->name('titip.items');
    Route::delete('/titip/{id}', [DeliveringController::class, 'delete'])->name('titip.delete');
    Route::patch('/titip/update/{id}', [DeliveringController::class, 'update'])->name('titip.update');

    Route::get('/users/picker', [UserController::class, 'getPicker']);

    Route::get('/loading', [LoadingContoroller::class, 'index'])->name('loading');
    Route::post('/loading', [LoadingContoroller::class, 'loading'])->name('loading');
    Route::get('/loading/items', [LoadingContoroller::class, 'getLoadingItems'])->name('loading.items');
    Route::get('/loading/items/{outletId}', [LoadingContoroller::class, 'getLoadingItemsByOutlet'])->name('loading.items.by-outlet');
    Route::patch('/loading/update/{id}', [LoadingContoroller::class, 'updateById'])->name('loading.update');
});
