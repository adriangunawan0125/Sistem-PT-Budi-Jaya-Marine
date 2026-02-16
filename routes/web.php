<?php


use App\Http\Controllers\AdminMarine\TimesheetController;
use App\Http\Controllers\ContactMessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminMarineController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AdminTransport\KelolaMitraController;
use App\Http\Controllers\AdminTransport\AdminTransportController;
use App\Http\Controllers\AdminTransport\CalonMitraTransportControlerMitraTransportController;
use App\Http\Controllers\AdminTransport\KelolaUnitController;
use App\Http\Controllers\AdminTransport\LaporanMitraController;
use App\Http\Controllers\ExMitraController;
use App\Http\Controllers\InvoiceController as ControllersInvoiceController;
use App\Http\Controllers\JaminanMitraController;
use App\Http\Controllers\PengeluaranInternalController;
use App\Http\Controllers\PengeluaranPajakController;
use App\Http\Controllers\PengeluaranTransportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminMarine\CompanyController;
use App\Http\Controllers\MitraTransportController;
use App\Http\Controllers\CalonMitraController;
use App\Http\Controllers\AdminTransport\CalonMitraController as AdminCalonMitraController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\owner_transport\OwnerTransportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminMarine\MarineInvoiceController;
//use App\Http\Controllers\PoMasukController;
//use App\Http\Controllers\PoSupplierController;
//use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\AdminMarine\MitraMarineController;
use App\Http\Controllers\AdminMarine\QuotationController;
use App\Http\Controllers\AdminMarine\PoMasukController;
use App\Http\Controllers\AdminMarine\PoSupplierController;
use App\Http\Controllers\AdminMarine\DeliveryOrderController;
use App\Http\Controllers\AdminMarine\PengeluaranPoController;



//PUBLIC 
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/hubungi-kami', [HomeController::class, 'contact'])->name('hubungi');
Route::get('/transport-service', [HomeController::class, 'transportService'])->name('transportservice');
Route::get('/marine-spareparts', [HomeController::class, 'marineSpareparts'])->name('marinespareparts');
Route::get('/marine-service', [HomeController::class, 'marineService'])->name('marinespareparts');
Route::get('/transport-gallery', [HomeController::class, 'transportgallery'])->name('transport.gallery');
Route::get('/service-gallery', [HomeController::class, 'servicegallery'])->name('service.gallery');
Route::get('/spareparts-gallery', [HomeController::class, 'sparepartsgallery'])->name('spareparts.gallery');
Route::get('/daftar-mitra', [HomeController::class, 'daftarMitra'])->name('daftar.mitra');
Route::post('/hubungi-kami', [ContactController::class, 'store'])->name('contact.store');
Route::post('/daftar-mitra', [CalonMitraController::class, 'store'])->name('mitra.store');


// AUTH    
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// OWNER TRANSPORT
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner',[OwnerTransportController::class, 'dashboard'])->name('owner.dashboard');

    // Laporan pemasukan Harian
    Route::get('/laporan-pemasukan-harian', [OwnerTransportController::class, 'laporanHarian'])->name('laporan-harian');
    // OWNER - INVOICE DETAIL (AMAN, GA NABRAK)

    
    // Laporan pemasukan Bulanan
    Route::get('/laporan-pemasukan-bulanan', [OwnerTransportController::class, 'laporanBulanan'])->name('laporan-bulanan');

    Route::get('/laporan-pengeluaran-internal', [OwnerTransportController::class, 'laporanPengeluaranInternal'])->name('owner_transport.laporan_pengeluaran');
    Route::get('pengeluaran-internal/{id}',[OwnerTransportController::class, 'detailPengeluaranInternal'])->name('pengeluaran_internal.detail');


   Route::get('/laporan-pengeluaran-transport',
    [OwnerTransportController::class, 'rekapPengeluaranTransport']
)->name('pengeluaran_transport.rekap');

Route::get('/laporan-pengeluaran-transport/laporan',
    [OwnerTransportController::class, 'laporanPengeluaranTransport']
)->name('owner_transport.laporan_pengeluaran_transport');

Route::get('/laporan-pengeluaran-transport/{id}',
    [OwnerTransportController::class, 'detailPengeluaranTransport']
)->name('pengeluaran_transport.show');

// TAMBAHAN DETAIL ITEM (HARUS PALING BAWAH)
Route::get('/laporan-pengeluaran-transport/item/{id}',
    [OwnerTransportController::class, 'detailItemPengeluaranTransport']
)->name('pengeluaran_transport.item_detail');

    Route::get('/laporan-pengeluaran-pajak', [OwnerTransportController::class, 'rekapPengeluaranPajak'])->name('pengeluaran_pajak.rekap');
    Route::get('/pengeluaran-pajak/{id}',[OwnerTransportController::class, 'detailPengeluaranPajak'])->name('pengeluaran_pajak.detail');

    // Laporan Mitra Aktif
    Route::get('/laporan-mitra-aktif', [OwnerTransportController::class, 'laporanMitraAktif'])->name('mitra.aktif');

    // Laporan Ex Mitra
    Route::get('/laporan-ex-mitra', [OwnerTransportController::class, 'laporanExMitra'])->name('mitra.ex');

    // Detail Mitra
    Route::get('/laporan-mitra/{id}', [OwnerTransportController::class, 'detailMitra']) ->name('mitra.detail');

// LAPORAN INVOICE

// Rekap / List invoice
// LAPORAN INVOICE (OWNER)

// Rekap invoice (LIST)
Route::get('/laporan-invoice', 
    [OwnerTransportController::class, 'laporanInvoice']
)->name('invoice.rekap');

// Detail invoice (HARUS PALING BAWAH)
Route::get('/laporan-invoice/mitra/{mitra}',
    [OwnerTransportController::class, 'detailInvoice']
)->whereNumber('mitra')
 ->name('invoiceowner.show');

Route::get('/pemasukan/{pemasukan}/detail', 
    [OwnerTransportController::class, 'detail']
)->name('pemasukan.detail');

   // tandai semua notifikasi dibaca
Route::post('/owner/notifikasi/read-all', function () {
    \App\Models\OwnerNotification::where('is_read', 0)->update([
        'is_read' => 1
    ]);
    return response()->json(['status' => 'ok']);
})->name('owner.notif.readall');

// tandai satu notifikasi dibaca
Route::post('/owner/notifikasi/read/{id}', function ($id) {
    $notif = \App\Models\OwnerNotification::find($id);
    if ($notif) {
        $notif->is_read = 1;
        $notif->save();
    }
    return response()->json(['status' => 'ok']);
})->name('owner.notif.read');


});

// ADMIN TRANSPORT
Route::middleware(['auth', 'role:admin_transport'])->group(function () {

    //dashboard
    Route::get('/admin-transport', [AdminTransportController::class, 'dashboard'])->name('admin.transport.dashboard');

    //unit
    Route::get('/admin-transport/unit', [KelolaUnitController::class, 'getIndex']);
    Route::get('/admin-transport/unit/create', [KelolaUnitController::class, 'getCreate']);
    Route::get('/admin-transport/unit/edit/{id}', [KelolaUnitController::class, 'getEdit']);
    Route::post('/admin-transport/unit/store', [KelolaUnitController::class, 'postStore']);
    Route::put('/admin-transport/unit/update/{id}', [KelolaUnitController::class, 'putUpdate']);
    Route::delete('/admin-transport/unit/delete/{id}', [KelolaUnitController::class, 'deleteDestroy']);

    //mitra
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
        Route::delete('/mitra/{mitra}',[KelolaMitraController::class, 'destroy']); 
     });

// INVOICE
// list invoice per mitra
Route::get('/invoice', [InvoiceController::class, 'index'])
    ->name('invoice.index');

// form create
Route::get('/invoice/create', [InvoiceController::class, 'create'])
    ->name('invoice.create');

// simpan invoice + items
Route::post('/invoice', [InvoiceController::class, 'store'])
    ->name('invoice.store');

// detail invoice (per mitra)
Route::get('/invoice/{mitra}', [InvoiceController::class, 'show'])
    ->name('invoice.show');

// EDIT INVOICE (SEMUA ITEM SEKALIGUS)
Route::get('/invoice/{invoice}/edit', [InvoiceController::class, 'edit'])
    ->name('invoice.edit');

Route::put('/invoice/{invoice}', [InvoiceController::class, 'update'])
    ->name('invoice.update');

// HAPUS INVOICE
Route::delete('/invoice/{invoice}', [InvoiceController::class, 'destroy'])
    ->name('invoice.destroy');

// STATUS
Route::post('/invoice/{invoice}/lunas', [InvoiceController::class, 'markLunas'])
    ->name('invoice.lunas');

// PRINT
Route::get('/invoice/{invoice}/print', [InvoiceController::class, 'print'])
    ->name('invoice.print');

Route::get('/invoice-item/{item}', [InvoiceController::class, 'showItem'])
    ->name('invoice-item.show');



    //pengeluaran internal
    Route::get('pengeluaran_internal/laporan',[PengeluaranInternalController::class, 'laporan'])->name('pengeluaran_internal.laporan');
    Route::get('pengeluaran_internal/pdf',[PengeluaranInternalController::class, 'pdf'])->name('pengeluaran_internal.pdf');
    Route::resource('pengeluaran_internal',PengeluaranInternalController::class)->except(['show']);
    

    //Pengeluaran Pajak
    Route::get('pengeluaran_pajak/laporan',[PengeluaranPajakController::class, 'laporan'])->name('pengeluaran_pajak.laporan');

    Route::get('pengeluaran_pajak/print',[PengeluaranPajakController::class, 'print'])->name('pengeluaran_pajak.print');

    Route::resource('pengeluaran_pajak',PengeluaranPajakController::class)->except(['show']);

    //Pengeluaran Transport
    Route::get('pengeluaran_transport/print',[PengeluaranTransportController::class, 'print'])->name('pengeluaran_transport.print');

    Route::get('pengeluaran_transport/laporan',[PengeluaranTransportController::class, 'laporan'])->name('pengeluaran_transport.laporan');

    Route::resource('pengeluaran_transport',PengeluaranTransportController::class)->except(['show']);

    //Jaminan Mitra
    Route::resource(    'jaminan_mitra', JaminanMitraController::class);

    //kontak hubungi kami
    Route::get('/contact', [ContactMessageController::class, 'index'])->name('contact.index');
    Route::get('/contact/{id}', [ContactMessageController::class, 'show'])->name('contact.show');
    Route::delete('/contact/{id}', [ContactMessageController::class, 'destroy'])->name('contact.destroy');
   
    //calon mitra
    Route::get('/calon-mitra', [AdminCalonMitraController::class, 'index'])->name('admin.calon-mitra.index');
    Route::get('/calon-mitra/{id}', [AdminCalonMitraController::class, 'show'])->name('calonmitra.show');
    Route::post('/calon-mitra/{id}/approve', [AdminCalonMitraController::class, 'approve']);
    Route::delete('/calon-mitra/{id}',        [AdminCalonMitraController::class, 'destroy']);

Route::post('/admin/notifikasi/read-all', function () {
    \App\Models\AdminNotification::where('is_read', 0)->update([
        'is_read' => 1
    ]);
    return response()->json(['status' => 'ok']);
})->name('admin.notif.readall');

    //PEMASUKAN 
    Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
    Route::post('/pemasukan/create', [PemasukanController::class, 'create'])->name('pemasukan.create');
    Route::post('/pemasukan/store', [PemasukanController::class, 'store'])->name('pemasukan.store');
    Route::get('/pemasukan/{id}/edit', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
    Route::put('/pemasukan/{id}/update', [PemasukanController::class, 'update'])->name('pemasukan.update');
    Route::delete('/pemasukan/{id}/delete', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
    Route::get('/pemasukan-laporan-harian', [PemasukanController::class, 'laporanHarian'])->name('pemasukan.laporan.harian');
    Route::get('/pemasukan-laporan-bulanan',[PemasukanController::class, 'laporanBulanan'])->name('pemasukan.laporan.bulanan');
    Route::get('pemasukan/print-harian', [PemasukanController::class, 'printHarian'])
    ->name('pemasukan.print.harian');

Route::get('pemasukan/print-bulanan', [PemasukanController::class, 'printBulanan'])->name('pemasukan.print.bulanan');

Route::resource('pemasukan', PemasukanController::class);
    
});

// ADMIN MARINE
Route::middleware(['auth', 'role:admin_marine'])->group(function () {
    Route::get('/admin-marine', function () {
  return view('admin_marine.dashboard');
    })->name('admin.marine.dashboard');


Route::get('/mitra-marine', [MitraMarineController::class, 'index'])->name('mitra-marine.index');
Route::get('/mitra-marine/create', [MitraMarineController::class, 'create'])->name('mitra-marine.create');
Route::post('/mitra-marine/store', [MitraMarineController::class, 'store'])->name('mitra-marine.store');
Route::get('/mitra-marine/{id}', [MitraMarineController::class, 'show'])->name('mitra-marine.show');
Route::get('/mitra-marine/edit/{id}', [MitraMarineController::class, 'edit'])->name('mitra-marine.edit');
Route::post('/mitra-marine/update/{id}', [MitraMarineController::class, 'update'])->name('mitra-marine.update');
Route::delete('/mitra-marine/delete/{id}', [MitraMarineController::class, 'destroy'])->name('mitra-marine.delete');
Route::get('/mitra-marine/show/{id}', [MitraMarineController::class, 'show'])->name('mitra-marine.show');

Route::resource('quotations', \App\Http\Controllers\AdminMarine\QuotationController::class);
// SUB ITEM
Route::post('quotations/{quotation}/subitem', 
    [\App\Http\Controllers\AdminMarine\QuotationController::class,'storeSubItem']
)->name('quotations.subitem.store');

Route::delete('subitem/{subItem}', 
    [\App\Http\Controllers\AdminMarine\QuotationController::class,'deleteSubItem']
)->name('quotations.subitem.delete');

// ITEM
Route::post('subitem/{subItem}/item', 
    [\App\Http\Controllers\AdminMarine\QuotationController::class,'storeItem']
)->name('quotations.item.store');

Route::delete('item/{item}', 
    [\App\Http\Controllers\AdminMarine\QuotationController::class,'deleteItem']
)->name('quotations.item.delete');
Route::post('quotations/{quotation}/bulk-save',
    [\App\Http\Controllers\AdminMarine\QuotationController::class,'bulkSave']
)->name('quotations.bulk.save');
Route::get('quotations/{quotation}/print', 
    [QuotationController::class, 'print']
)->name('quotations.print');



// TERMS
Route::post('quotations/{quotation}/term', 
    [\App\Http\Controllers\AdminMarine\QuotationController::class,'storeTerm']
)->name('quotations.term.store');

Route::delete('term/{term}', 
    [\App\Http\Controllers\AdminMarine\QuotationController::class,'deleteTerm']
)->name('quotations.term.delete');
   
    
    Route::resource('companies', CompanyController::class);

    Route::resource('marine-invoices', MarineInvoiceController::class);
    Route::get('/marine-invoices/{id}/print',[MarineInvoiceController::class, 'print'])->name('marine-invoices.print');

    
    

Route::resource('po-masuk', PoMasukController::class);

/* ================= PO SUPPLIER ================= */
/* ================= PO SUPPLIER ================= */

// index
Route::get('po-supplier', [PoSupplierController::class, 'index'])
    ->name('po-supplier.index');

// create (pakai route model binding)
Route::get('po-supplier/create/{poMasuk}', [PoSupplierController::class, 'create'])
    ->name('po-supplier.create');

// store
Route::post('po-supplier', [PoSupplierController::class, 'store'])
    ->name('po-supplier.store');

    Route::get('po-supplier/{poSupplier}', [PoSupplierController::class, 'show'])
    ->name('po-supplier.show');

// edit
Route::get('po-supplier/{poSupplier}/edit', [PoSupplierController::class, 'edit'])
    ->name('po-supplier.edit');

// update
Route::put('po-supplier/{poSupplier}', [PoSupplierController::class, 'update'])
    ->name('po-supplier.update');

// destroy
Route::delete('po-supplier/{poSupplier}', [PoSupplierController::class, 'destroy'])
    ->name('po-supplier.destroy');
Route::get('po-supplier/{poSupplier}/print',
    [PoSupplierController::class, 'print']
)->name('po-supplier.print');

/* ================= DELIVERY ORDER ================= */

// List DO per PO
Route::get('po-masuk/{poMasuk}/delivery-orders', 
    [DeliveryOrderController::class, 'index']
)->name('delivery-order.index');

// Form create DO
Route::get('po-masuk/{poMasuk}/delivery-orders/create', 
    [DeliveryOrderController::class, 'create']
)->name('delivery-order.create');

// Store DO
Route::post('delivery-orders', 
    [DeliveryOrderController::class, 'store']
)->name('delivery-order.store');

// Show DO
Route::get('delivery-orders/{deliveryOrder}', 
    [DeliveryOrderController::class, 'show']
)->name('delivery-order.show');

// Edit DO
Route::get('delivery-orders/{deliveryOrder}/edit', 
    [DeliveryOrderController::class, 'edit']
)->name('delivery-order.edit');

// Update DO
Route::put('delivery-orders/{deliveryOrder}', 
    [DeliveryOrderController::class, 'update']
)->name('delivery-order.update');

// Delete DO
Route::delete('delivery-orders/{deliveryOrder}', 
    [DeliveryOrderController::class, 'destroy']
)->name('delivery-order.destroy');

Route::get('delivery-order/{deliveryOrder}/print',
    [DeliveryOrderController::class, 'print']
)->name('delivery-order.print');

// PO Masuk Status
Route::patch('po-masuk/{poMasuk}/approve', [PoMasukController::class,'approve'])
    ->name('po-masuk.approve');

Route::patch('po-masuk/{poMasuk}/close', [PoMasukController::class,'close'])
    ->name('po-masuk.close');

// Delivery Order Status
Route::patch('delivery-order/{deliveryOrder}/deliver', [DeliveryOrderController::class,'markDelivered'])
    ->name('delivery-order.deliver');
Route::patch('po-supplier/{poSupplier}/approve',
    [PoSupplierController::class,'approve'])
    ->name('po-supplier.approve');

Route::patch('po-supplier/{poSupplier}/cancel',
    [PoSupplierController::class,'cancel'])
    ->name('po-supplier.cancel');
Route::resource('pengeluaran-po', PengeluaranPoController::class);
    Route::get('pengeluaran-po/create/{poMasuk}', 
    [PengeluaranPoController::class,'create'])
    ->name('pengeluaran-po.create');

Route::post('pengeluaran-po/store', 
    [PengeluaranPoController::class,'store'])
    ->name('pengeluaran-po.store');

Route::get('pengeluaran-po/edit/{pengeluaranPo}', 
    [PengeluaranPoController::class,'edit'])
    ->name('pengeluaran-po.edit');

Route::put('pengeluaran-po/update/{pengeluaranPo}', 
    [PengeluaranPoController::class,'update'])
    ->name('pengeluaran-po.update');

Route::delete('pengeluaran-po/destroy/{pengeluaranPo}', 
    [PengeluaranPoController::class,'destroy'])
    ->name('pengeluaran-po.destroy');
});

