<?php

use App\Http\Controllers\ContactMessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminMarineController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AdminTransport\KelolaMitraController;
use App\Http\Controllers\AdminTransport\AdminTransportController;
use App\Http\Controllers\AdminTransport\CalonMitraTransportControllerMitraTransportController;
use App\Http\Controllers\AdminTransport\KelolaUnitController;
use App\Http\Controllers\AdminTransport\LaporanMitraController;
use App\Http\Controllers\ExMitraController;
use App\Http\Controllers\InvoiceController as ControllersInvoiceController;
use App\Http\Controllers\JaminanMitraController;
use App\Http\Controllers\PengeluaranInternalController;
use App\Http\Controllers\PengeluaranPajakController;
use App\Http\Controllers\PengeluaranTransportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MitraTransportController;
use App\Http\Controllers\CalonMitraController;
use App\Http\Controllers\AdminTransport\CalonMitraController as AdminCalonMitraController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\owner_transport\OwnerTransportController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/hubungi-kami', [HomeController::class, 'contact'])->name('hubungi');
Route::get('/transport-service', [HomeController::class, 'transportService'])->name('transportservice');
Route::get('/marine-spareparts', [HomeController::class, 'marineSpareparts'])
    ->name('marinespareparts');
Route::get('/marine-service', [HomeController::class, 'marineService'])
    ->name('marinespareparts');
Route::get('/transport-gallery', [HomeController::class, 'transportgallery'])
    ->name('transport.gallery');
Route::get('/service-gallery', [HomeController::class, 'servicegallery'])
    ->name('service.gallery');
Route::get('/spareparts-gallery', [HomeController::class, 'sparepartsgallery'])
    ->name('spareparts.gallery');
Route::get('/daftar-mitra', [HomeController::class, 'daftarMitra'])
    ->name('daftar.mitra');

use App\Http\Controllers\ContactController;

Route::post('/hubungi-kami', [ContactController::class, 'store'])
    ->name('contact.store');

Route::post('/daftar-mitra', [CalonMitraController::class, 'store'])
    ->name('mitra.store');


// ADMIN   
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner', function () {
        return view('owner_transport.dashboard');
    })->name('owner.dashboard');

    Route::get('/laporan-pemasukan-harian', [OwnerTransportController::class, 'laporanHarian'])->name('laporan-harian');

    // Laporan Bulanan
    Route::get('/laporan-pemasukan-bulanan', [OwnerTransportController::class, 'laporanBulanan'])->name('laporan-bulanan');
});

Route::middleware(['auth', 'role:admin_marine'])->group(function () {
    Route::get('/admin-marine', function () {
        return view('admin_marine.dashboard');
    })->name('admin.marine.dashboard');
});

Route::middleware(['auth', 'role:admin_transport'])->group(function () {
    Route::get('/admin-transport', [AdminTransportController::class, 'dashboard'])
        ->name('admin.transport.dashboard');


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



    Route::prefix('admin-transport')->group(function () {
        Route::get('/mitra', [KelolaMitraController::class, 'getIndex']);
        Route::get('/mitra/berakhir', [KelolaMitraController::class, 'getBerakhir']);
        Route::get('/mitra/create', [KelolaMitraController::class, 'getCreate']);
        Route::post('/mitra', [KelolaMitraController::class, 'postStore']);
        Route::get('/mitra/{id}/edit', [KelolaMitraController::class, 'getEdit']);
        Route::put('/mitra/{id}', [KelolaMitraController::class, 'putUpdate']);
        Route::patch('/mitra/{id}/akhiri-kontrak', [KelolaMitraController::class, 'patchAkhiriKontrak']);
        Route::patch('/mitra/{id}/aktifkan', [KelolaMitraController::class, 'patchAktifkan']);
        Route::get('/mitra/{id}', [KelolaMitraController::class, 'show']);
        Route::delete(
            '/mitra/{mitra}',
            [KelolaMitraController::class, 'destroy']
        );
    });


    Route::get('/invoice-item/{id}/edit', [InvoiceItemController::class, 'edit'])
        ->name('invoice-item.edit');

    Route::put(
        '/invoice-item/{id}',
        [InvoiceItemController::class, 'update']
    )->name('invoice-item.update');
    Route::delete(
        '/invoice-item/{id}',
        [InvoiceItemController::class, 'destroy']
    )->name('invoice-item.destroy');

    Route::resource('invoice', InvoiceController::class);

    Route::resource('pengeluaran_internal', PengeluaranInternalController::class)
        ->except(['show']); // hapus route show

    Route::get('pengeluaran_internal/laporan', [PengeluaranInternalController::class, 'laporan'])->name('pengeluaran_internal.laporan');


    // Resource routes untuk CRUD (tanpa show)
    Route::resource('pengeluaran_pajak', PengeluaranPajakController::class)->except(['show']);

    // Route khusus untuk laporan per bulan
    Route::get('pengeluaran_pajak/laporan', [PengeluaranPajakController::class, 'laporan'])->name('pengeluaran_pajak.laporan');

    Route::get('pengeluaran_pajak/laporan', [PengeluaranPajakController::class, 'laporan'])
        ->name('pengeluaran_pajak.laporan');



    Route::resource('pengeluaran_transport', PengeluaranTransportController::class)->except(['show']);
    Route::get('pengeluaran_transport/laporan', [PengeluaranTransportController::class, 'laporan'])->name('pengeluaran_transport.laporan');

    Route::resource('jaminan_mitra', JaminanMitraController::class);

    Route::get('/contact', [ContactMessageController::class, 'index'])
        ->name('contact.index');

    Route::get('/contact/{id}', [ContactMessageController::class, 'show'])
        ->name('contact.show');

    Route::delete('/contact/{id}', [ContactMessageController::class, 'destroy'])
        ->name('contact.destroy');

    Route::get('/calon-mitra', [AdminCalonMitraController::class, 'index'])
        ->name('admin.calonmitra');

    Route::delete('/admin/calon-mitra/{id}', [AdminCalonMitraController::class, 'destroy'])
        ->name('admin.calonmitra.destroy');

    /* ================= PEMASUKAN ================= */

    Route::get('/pemasukan', [PemasukanController::class, 'index'])
        ->name('pemasukan.index');

    Route::get('/pemasukan/create', [PemasukanController::class, 'create'])
        ->name('pemasukan.create');

    Route::post('/pemasukan/store', [PemasukanController::class, 'store'])
        ->name('pemasukan.store');

    Route::get('/pemasukan/{id}/edit', [PemasukanController::class, 'edit'])
        ->name('pemasukan.edit');

    Route::put('/pemasukan/{id}/update', [PemasukanController::class, 'update'])
        ->name('pemasukan.update');

    Route::delete('/pemasukan/{id}/delete', [PemasukanController::class, 'destroy'])
        ->name('pemasukan.destroy');

    /* ===== LAPORAN HARIAN ===== */
    Route::get('/pemasukan-laporan-harian', [PemasukanController::class, 'laporanHarian'])
        ->name('pemasukan.laporan.harian');

    Route::get(
        '/pemasukan-laporan-bulanan',
        [PemasukanController::class, 'laporanBulanan']
    )->name('pemasukan.laporan.bulanan');



    Route::prefix('owner-transport')->name('owner_transport.')->group(function () {
        // Laporan Harian

    });
});


//test