<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KontribusiTokoController;
use App\Http\Controllers\TrenPenjualanTokoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\TrenPenjualanGlobalController;

// Landing Page
Route::get('/', function () {
    return view('landing.landing');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess']);

// REGISTER
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'registerProcess']);

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| OWNER
|--------------------------------------------------------------------------
*/

// Hanya 1 Prefix, dan semua rute owner dilindungi Middleware Auth
Route::prefix('owner')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');

    // Tren Global
    Route::get(
        '/tren-global',
        [TrenPenjualanGlobalController::class, 'index']
    )->name('owner.tren-global');

    // Tren Toko
    Route::get('/tren-penjualan-toko', [TrenPenjualanTokoController::class, 'trenPenjualanToko'])
        ->name('owner.tren-toko');

    // Kontribusi
    Route::get('/kontribusi-toko/{tahun?}', [KontribusiTokoController::class, 'kontribusiToko'])
        ->name('owner.kontribusi-toko');

    // Kelola Cabang
    Route::get('/kelola-cabang', [BranchController::class, 'index'])->name('owner.kelola-cabang');
    Route::post('/kelola-cabang', [BranchController::class, 'store'])->name('owner.kelola-cabang.store');
    Route::put('/kelola-cabang/{id}', [BranchController::class, 'update'])->name('owner.kelola-cabang.update');
    Route::delete('/kelola-cabang/{id}', [BranchController::class, 'destroy'])->name('owner.kelola-cabang.destroy');

    // Daftar Toko
    Route::get('/daftar-toko', [BranchController::class, 'daftarToko'])->name('owner.daftar-toko');

    // Proses Edas
    Route::get(
        '/proses-edas/{tahun}',
        [KontribusiTokoController::class, 'prosesEdas']
    );

    // Proses forward chaining
    Route::get(
        '/proses-status-toko/{yearAwal}/{yearAkhir}',
        [TrenPenjualanTokoController::class, 'prosesStatusToko']
    );
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

// Rute khusus untuk Admin, dilindungi oleh middleware auth
Route::prefix('admin')->middleware('auth')->group(function () {
    
    // Dashboard Admin 
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    //DATA TRANSAKSI
    Route::get('/data-transaksi', [AdminTransactionController::class, 'index'])->name('admin.transaksi');

    Route::post('/data-transaksi/import', [AdminTransactionController::class, 'importCsv'])->name('admin.transaksi.import');
    Route::delete('/data-transaksi/{id}', [AdminTransactionController::class, 'destroy'])->name('admin.transaksi.destroy');

    // input data
    Route::get('/input-data', [AdminTransactionController::class, 'create'])->name('admin.input');
    Route::post('/input-data', [AdminTransactionController::class, 'store'])->name('admin.input.store');
    Route::delete('/data-transaksi/{id}', [AdminTransactionController::class, 'destroy'])->name('admin.transaksi.destroy');
    Route::get('/data-transaksi/{id}/edit', [AdminTransactionController::class, 'edit'])->name('admin.transaksi.edit');
    Route::put('/data-transaksi/{id}', [AdminTransactionController::class, 'update'])->name('admin.transaksi.update');
});