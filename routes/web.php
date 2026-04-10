<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BoardingListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveringController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LoadingContoroller;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PickController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeliverController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('auth.login'));
Route::post('/signin', [AuthController::class, 'signIn']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
    Route::patch('/loading/update/{id}', [LoadingContoroller::class, 'updateLoading'])->name('loading.update');
    Route::patch('/loading/split/{id}', [LoadingContoroller::class, 'splitItem'])->name('loading.split');
    Route::get('/loading/history', [LoadingContoroller::class, 'history']);
    Route::get('/loading/get/{id}', [LoadingContoroller::class, 'checkId']);
    Route::get('/loading/print/{id}', [LoadingContoroller::class,  'printById'])->name('loading.print');
    Route::get('/loading/{id}', [LoadingContoroller::class,  'loadingDetail'])->name('loading.detail');

    Route::get('/master/users', [MasterController::class, 'index'])->name('master.users');
    Route::get('/master/users/items', [MasterController::class, 'userItems'])->name('master.users.items');
    Route::post('/master/user/create', [MasterController::class, 'userCreate'])->name('master.user.create');
    Route::patch('/master/user/update/{id}', [MasterController::class, 'userUpdate']);
    Route::delete('/master/user/delete/{id}', [MasterController::class, 'userDelete']);


    Route::get('/master/department', [DepartmentController::class, 'index'])->name('master.department');
    Route::get('/master/department/items', [DepartmentController::class, 'items'])->name('department.items');
    Route::post('/master/department/create', [DepartmentController::class, 'store'])->name('department.create');
    Route::patch('/master/department/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
    Route::delete('/master/department/delete/{id}', [DepartmentController::class, 'delete'])->name('department.delete');

    Route::get('/master/outlet', [OutletController::class, 'index'])->name('master.outlet');
    Route::get('/master/outlet/items', [OutletController::class, 'items'])->name('master.outlet');
    Route::post('/master/outlet/create', [OutletController::class, 'store']);
    Route::patch('/master/outlet/update/{id}', [OutletController::class, 'update']);
    Route::delete('/master/outlet/delete/{id}', [OutletController::class, 'destroy']);


    Route::get('/picker/dashboard', [DashboardController::class, 'pickerDashboard']);
    Route::get('/picker/print', [DashboardController::class, 'printBarcode']);

    Route::get('/driver/dashboard', [DashboardController::class, 'driverDashboard']);

    Route::post('/deliver/create', [DeliverController::class, 'create']);
});
