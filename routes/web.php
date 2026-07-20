<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BoardingListController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveringController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LoadingContoroller;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PickController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeliverController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/signin', [AuthController::class, 'signIn']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::get('/master/users', [MasterController::class, 'index'])->name('master.users');
    Route::get('/master/users/items', [MasterController::class, 'userItems'])->name('master.users.items');
    Route::post('/master/user/create', [MasterController::class, 'userCreate'])->name('master.user.create');
    Route::patch('/master/user/update/{id}', [MasterController::class, 'userUpdate']);
    Route::delete('/master/user/delete/{id}', [MasterController::class, 'userDelete']);
});

Route::middleware(['auth', 'role:BOARDER,ADMIN,SPV'])->group(function () {
    Route::get('/boarding', [BoardingListController::class, 'index'])->name('boarding');
    Route::post('/boarding', [BoardingListController::class, 'store'])->name('boarding.store');
    Route::get('/boarding/items', [BoardingListController::class, 'getAll'])->name('boarding.items');

    Route::get('/titip', [DeliveringController::class, 'index'])->name('titip');
    Route::post('/titip', [DeliveringController::class, 'create'])->name('titip.create');
    Route::get('/titip/items', [DeliveringController::class, 'getAll'])->name('titip.items');
    Route::delete('/titip/{id}', [DeliveringController::class, 'delete'])->name('titip.delete');
    Route::patch('/titip/update/{id}', [DeliveringController::class, 'update'])->name('titip.update');
});

Route::middleware(['auth', 'role:ADMIN,SPV'])->group(function () {
    Route::get('/picking', [BarangController::class, 'index'])->name('barang');
    Route::post('/barang', [BarangController::class, 'create']);
    Route::delete('/barang/{id}', [BarangController::class, 'delete'])->name('barang.delete');
    Route::get('/barang/items', [BarangController::class, 'getAll']);
    Route::patch('/barang/{id}/update-picker', [BarangController::class, 'updatePicker'])->name('barang.update-picker');

    Route::get('/users/picker', [UserController::class, 'getPicker']);
    Route::get('/pick', [PickController::class, 'index'])->name('barang.pick');
    Route::get('/pick/items', [PickController::class, 'getAll'])->name('pick.items');
    Route::patch('/pick/end', [PickController::class, 'end']);
    Route::patch('/pick/{id}/end', [PickController::class, 'endPick'])->name('pick.end');

    Route::get('/suratjalan', [SuratJalanController::class, 'index'])->name('daftar-sj');
    Route::get('/surat-jalan-detail', [SuratJalanController::class, 'getDetail'])->name('surat-jalan.detail');
    Route::get('/surat-jalan/data', [SuratJalanController::class, 'getData'])->name('surat-jalan.data');

    // Route::get('/boarding', [BoardingListController::class, 'index'])->name('boarding');
    // Route::post('/boarding', [BoardingListController::class, 'store'])->name('boarding.store');
    // Route::get('/boarding/items', [BoardingListController::class, 'getAll'])->name('boarding.items');

    // Route::get('/titip', [DeliveringController::class, 'index'])->name('titip');
    // Route::post('/titip', [DeliveringController::class, 'create'])->name('titip.create');
    // Route::get('/titip/items', [DeliveringController::class, 'getAll'])->name('titip.items');
    // Route::delete('/titip/{id}', [DeliveringController::class, 'delete'])->name('titip.delete');
    // Route::patch('/titip/update/{id}', [DeliveringController::class, 'update'])->name('titip.update');

    Route::get('/loading', [LoadingContoroller::class, 'index'])->name('loading');
    Route::post('/loading', [LoadingContoroller::class, 'loading'])->name('loading.store');
    Route::get('/loading/items', [LoadingContoroller::class, 'getLoadingItems'])->name('loading.items');
    Route::get('/loading/items/{outletId}', [LoadingContoroller::class, 'getLoadingItemsByOutlet'])->name('loading.items.by-outlet');
    Route::patch('/loading/update/{id}', [LoadingContoroller::class, 'updateLoading'])->name('loading.update');
    Route::get('/loading/preview/{id}', [LoadingContoroller::class, 'preview'])->name('loading.preview');
    Route::get('/loading/print/{id}', [LoadingContoroller::class, 'printById'])->name('loading.print');
    Route::get('/loading/history', [LoadingContoroller::class, 'history']);
    Route::get('/loading/{id}', [LoadingContoroller::class, 'loadingDetail'])->name('loading.detail');

    Route::get('/master/department', [DepartmentController::class, 'index'])->name('master.department');
    Route::get('/master/department/items', [DepartmentController::class, 'items'])->name('department.items');
    Route::post('/master/department/create', [DepartmentController::class, 'store'])->name('department.create');
    Route::patch('/master/department/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
    Route::delete('/master/department/delete/{id}', [DepartmentController::class, 'delete'])->name('department.delete');

    Route::get('/master/outlet', [OutletController::class, 'index'])->name('master.outlet');
    Route::get('/master/outlet/items', [OutletController::class, 'items'])->name('master.outlet.items');
    Route::post('/master/outlet/create', [OutletController::class, 'store']);
    Route::patch('/master/outlet/update/{id}', [OutletController::class, 'update']);
    Route::delete('/master/outlet/delete/{id}', [OutletController::class, 'destroy']);
    Route::patch('/master/outlet/activate/{id}', [OutletController::class, 'activate']);

    Route::get('/master/jenis-barang', [JenisController::class, 'index'])->name('master.jenis');
    Route::get('/master/jenis-barang/items', [JenisController::class, 'items'])->name('master.jenis.items');
    Route::post('/master/jenis-barang/create', [JenisController::class, 'store']);
    Route::patch('/master/jenis-barang/update/{id}', [JenisController::class, 'update']);
    Route::delete('/master/jenis-barang/delete/{id}', [JenisController::class, 'destroy']);

    Route::get('/admin/dashboard/picker', [DashboardController::class, 'pickerPerformance']);
    Route::get('/admin/dashboard/picking', [DashboardController::class, 'pickingData']);
    Route::get('/admin/dashboard/boarding', [DashboardController::class, 'boardingData']);
    Route::get('/admin/dashboard/loading', [DashboardController::class, 'loadingData']);
    Route::get('/admin/dashboard/delivering', [DashboardController::class, 'deliveringData']);
    Route::get('/admin/dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('/admin/dashboard/claim', [DashboardController::class, 'claimData']);
    Route::get('/admin/dashboard/driver', [DashboardController::class, 'driverPerformance']);

    Route::post('/admin/claim/approve/{id}', [DashboardController::class, 'approveClaim'])->name('admin.claim.approve');
});

Route::middleware(['auth', 'role:PICKER'])->group(function () {
    Route::get('/picker/dashboard', [DashboardController::class, 'pickerDashboard']);
    Route::get('/picker/print', [DashboardController::class, 'printBarcode']);
});

Route::middleware(['auth', 'role:DRIVER'])->group(function () {
    Route::post('/deliver/create', [DeliverController::class, 'create']);
    Route::post('/deliver/clock-in/{id}', [DeliverController::class, 'clockIn']);
    Route::get('/driver/dashboard', [DashboardController::class, 'driverDashboard']);
    Route::get('/driver/camera/{id}', [DeliverController::class, 'camera']);
    Route::get('/loading/get/{id}', [LoadingContoroller::class, 'checkId']);
});

Route::middleware(['auth', 'role:PIC'])->group(function () {
    Route::post('/deliver/clock-out/{id}', [DeliverController::class, 'clockOut']);

    Route::post('/rating/driver/{id}', [RatingController::class, 'store']);
    Route::post('/claim', [ClaimController::class, 'store']);

    Route::get('/pic/dashboard', [DashboardController::class, 'picDashboard']);
    Route::get('/pic/detail/{id}', [DeliverController::class, 'picDetail']);
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::get('/barang', [BarangController::class, 'index'])->name('barang');
//     Route::post('/barang', [BarangController::class, 'create']);
//     Route::delete('/barang/{id}', [BarangController::class, 'delete'])->name('barang.delete');
//     Route::get('/barang/items', [BarangController::class, 'getAll']);
//     Route::patch('/barang/{id}/update-picker', [BarangController::class, 'updatePicker'])->name('barang.update-picker');

// Route::get('/pick', [PickController::class, 'index'])->name('barang.pick');
// Route::get('/pick/items', [PickController::class, 'getAll'])->name('barang.pick');
// Route::patch('/pick/{id}/end', [PickController::class, 'endPick'])->name('pick.end');
// Route::patch('/pick/end', [PickController::class, 'end']);

//     Route::get('/boarding', [BoardingListController::class, 'index'])->name('boarding');
//     Route::post('/boarding', [BoardingListController::class, 'store'])->name('boarding.store');
//     Route::get('/boarding/items', [BoardingListController::class, 'getAll'])->name('boarding.items');

//     Route::get('/titip', [DeliveringController::class, 'index'])->name('titip');
//     Route::post('/titip', [DeliveringController::class, 'create'])->name('titip.create');
//     Route::get('/titip/items', [DeliveringController::class, 'getAll'])->name('titip.items');
//     Route::delete('/titip/{id}', [DeliveringController::class, 'delete'])->name('titip.delete');
//     Route::patch('/titip/update/{id}', [DeliveringController::class, 'update'])->name('titip.update');

//     Route::get('/users/picker', [UserController::class, 'getPicker']);

//     Route::get('/loading', [LoadingContoroller::class, 'index'])->name('loading');
//     Route::post('/loading', [LoadingContoroller::class, 'loading'])->name('loading');
//     Route::get('/loading/items', [LoadingContoroller::class, 'getLoadingItems'])->name('loading.items');
//     Route::get('/loading/items/{outletId}', [LoadingContoroller::class, 'getLoadingItemsByOutlet'])->name('loading.items.by-outlet');
//     Route::patch('/loading/update/{id}', [LoadingContoroller::class, 'updateLoading'])->name('loading.update');
//     Route::get('/loading/history', [LoadingContoroller::class, 'history']);
//     Route::get('/loading/get/{id}', [LoadingContoroller::class, 'checkId']);
//     Route::get('/loading/print/{id}', [LoadingContoroller::class,  'printById'])->name('loading.print');
//     Route::get('/loading/{id}', [LoadingContoroller::class,  'loadingDetail'])->name('loading.detail');

//     Route::get('/master/users', [MasterController::class, 'index'])->name('master.users');
//     Route::get('/master/users/items', [MasterController::class, 'userItems'])->name('master.users.items');
//     Route::post('/master/user/create', [MasterController::class, 'userCreate'])->name('master.user.create');
//     Route::patch('/master/user/update/{id}', [MasterController::class, 'userUpdate']);
//     Route::delete('/master/user/delete/{id}', [MasterController::class, 'userDelete']);


//     Route::get('/master/department', [DepartmentController::class, 'index'])->name('master.department');
//     Route::get('/master/department/items', [DepartmentController::class, 'items'])->name('department.items');
//     Route::post('/master/department/create', [DepartmentController::class, 'store'])->name('department.create');
//     Route::patch('/master/department/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
//     Route::delete('/master/department/delete/{id}', [DepartmentController::class, 'delete'])->name('department.delete');

//     Route::get('/master/outlet', [OutletController::class, 'index'])->name('master.outlet');
//     Route::get('/master/outlet/items', [OutletController::class, 'items'])->name('master.outlet');
//     Route::post('/master/outlet/create', [OutletController::class, 'store']);
//     Route::patch('/master/outlet/update/{id}', [OutletController::class, 'update']);
//     Route::delete('/master/outlet/delete/{id}', [OutletController::class, 'destroy']);

//     Route::get('/master/jenis-barang', [JenisController::class, 'index'])->name('master.jenis');



//     Route::get('/picker/dashboard', [DashboardController::class, 'pickerDashboard']);
//     Route::get('/picker/print', [DashboardController::class, 'printBarcode']);

// Route::get('/driver/dashboard', [DashboardController::class, 'driverDashboard']);
// Route::get('/driver/camera/{id}', [DeliverController::class, 'camera']);

//     Route::get('/pic/dashboard', [DashboardController::class, 'picDashboard']);
//     Route::get('/pic/detail/{id}', [DeliverController::class, 'picDetail']);
//     Route::post('/deliver/create', [DeliverController::class, 'create']);
//     Route::post('/deliver/clock-in/{id}', [DeliverController::class, 'clockIn']);
//     Route::post('/deliver/clock-out/{id}', [DeliverController::class, 'clockOut']);

//     Route::post('/rating/driver/{id}', [RatingController::class, 'store']);
//     Route::post('/claim', [ClaimController::class, 'store']);

//     Route::get('/admin/dashboard/picker', [DashboardController::class, 'pickerPerformance']);
//     Route::get('/admin/dashboard/picking', [DashboardController::class, 'pickingData']);
//     Route::get('/admin/dashboard/boarding', [DashboardController::class, 'boardingData']);
//     Route::get('/admin/dashboard/loading', [DashboardController::class, 'loadingData']);
//     Route::get('/admin/dashboard/summary', [DashboardController::class, 'summary']);
// });
