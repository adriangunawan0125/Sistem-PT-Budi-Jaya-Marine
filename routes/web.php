<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminMarineController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Admintransport\KelolaMitraController;
use App\Http\Controllers\AdminTransportController;
use App\Http\Controllers\Admintransport\KelolaUnitController;
use App\Http\Controllers\AdminTransport\LaporanMitraController;
use App\Http\Controllers\InvoiceController as ControllersInvoiceController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner', function () {
        return view('owner.dashboard');
    })->name('owner.dashboard');
});

Route::middleware(['auth', 'role:admin_marine'])->group(function () {
    Route::get('/admin-marine', function () {
        return view('admin_marine.dashboard');
    })->name('admin.marine.dashboard');
});

Route::middleware(['auth', 'role:admin_transport'])->group(function () {
    Route::get('/admin-transport', function () {
        return view('admin_transport.dashboard');
    })->name('admin.transport.dashboard');


    //unit
    Route::get('/admin-transport/unit', [KelolaUnitController::class, 'getIndex']);
    Route::get('/admin-transport/unit/create', [KelolaUnitController::class, 'getCreate']);
    Route::get('/admin-transport/unit/edit/{id}', [KelolaUnitController::class, 'getEdit']);

    // POST
    Route::post('/admin-transport/unit/store', [KelolaUnitController::class, 'postStore']);

    // PUT
    Route::put('/admin-transport/unit/update/{id}', [KelolaUnitController::class, 'putUpdate']);

    // DELETE
    Route::delete('/admin-transport/unit/delete/{id}', [KelolaUnitController::class, 'deleteDestroy']);

    //Mitra
    Route::get(
        '/admin-transport/mitra',
        [KelolaMitraController::class, 'getIndex']
    );

    Route::get(
        '/admin-transport/mitra/create',
        [KelolaMitraController::class, 'getCreate']
    );

    Route::post(
        '/admin-transport/mitra/store',
        [KelolaMitraController::class, 'postStore']
    );

    Route::get(
        '/admin-transport/mitra/edit/{id}',
        [KelolaMitraController::class, 'getEdit']
    );

    Route::put(
        '/admin-transport/mitra/update/{id}',
        [KelolaMitraController::class, 'putUpdate']
    );

    Route::delete(
        '/admin-transport/mitra/delete/{id}',
        [KelolaMitraController::class, 'deleteDestroy']
    );
    //Laporan Mitra

    Route::get('/admin-transport/laporan/mitra', [LaporanMitraController::class, 'getIndex']);
    Route::get('/admin-transport/laporan/mitra/excel', [LaporanMitraController::class, 'exportExcel']);

    Route::get('/laporan/mitra/pdf', [LaporanMitraController::class, 'exportPdf']);



    Route::put(
        '/invoice-item/{id}',
        [InvoiceItemController::class, 'update']
    )->name('invoice-item.update');
    Route::delete(
        '/invoice-item/{id}',
        [InvoiceItemController::class, 'destroy']
    )->name('invoice-item.destroy');

    Route::resource('invoice', InvoiceController::class);
});
