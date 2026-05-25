<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\StokMengendapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReturBahanController;

// ========== GUEST ROUTES (BELUM LOGIN) ==========
Route::get('/', function () {
    return redirect()->route('login');
});

// ========== AUTH ROUTES (LOGIN, REGISTER, FORGOT PASSWORD) ==========
require __DIR__.'/auth.php';

// ========== ROUTE YANG MEMBUTUHKAN LOGIN ==========
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/filter-7-hari', [DashboardController::class, 'filter7Hari'])->name('dashboard.filter-7-hari');
    
    // ========== MANAJEMEN BAHAN ==========
    Route::resource('bahan', BahanController::class);
    Route::get('/bahan/export/excel', [BahanController::class, 'exportExcel'])->name('bahan.export.excel');
    Route::get('/bahan/export/pdf', [BahanController::class, 'exportPdf'])->name('bahan.export.pdf');
    
    // ========== STOK MASUK ==========
    Route::prefix('stok-masuk')->name('stok-masuk.')->group(function () {
        Route::get('/', [StokMasukController::class, 'index'])->name('index');
        Route::post('/', [StokMasukController::class, 'store'])->name('store');
        Route::get('/filter', [StokMasukController::class, 'filter'])->name('filter');
        Route::get('/history', [StokMasukController::class, 'history'])->name('history');
        Route::delete('/{stokMasuk}', [StokMasukController::class, 'destroy'])->name('destroy');
        Route::get('/{stokMasuk}', [StokMasukController::class, 'show'])->name('show');
        Route::get('/export/excel', [StokMasukController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [StokMasukController::class, 'exportPdf'])->name('export.pdf');
    });
    
    // ========== STOK KELUAR ==========
    Route::prefix('stok-keluar')->name('stok-keluar.')->group(function () {
        Route::get('/', [StokKeluarController::class, 'index'])->name('index');
        Route::post('/', [StokKeluarController::class, 'store'])->name('store');
        Route::get('/filter', [StokKeluarController::class, 'filter'])->name('filter');
        Route::get('/history', [StokKeluarController::class, 'history'])->name('history');
        Route::get('/export/excel', [StokKeluarController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [StokKeluarController::class, 'exportPdf'])->name('export.pdf');
    });
    
    // ========== STOK OPNAME ==========
    Route::prefix('stok-opname')->name('stok-opname.')->group(function () {
        Route::get('/', [StokOpnameController::class, 'index'])->name('index');
        Route::get('/create', [StokOpnameController::class, 'create'])->name('create');
        Route::post('/', [StokOpnameController::class, 'store'])->name('store');
        Route::get('/filter', [StokOpnameController::class, 'filter'])->name('filter');
        Route::get('/{stokOpname}', [StokOpnameController::class, 'show'])->name('show');
    });
    
    // ========== MANAJEMEN MENU ==========
    Route::resource('menu', MenuController::class);
    Route::post('/menu/{menu}/komposisi', [MenuController::class, 'updateKomposisi'])->name('menu.komposisi');
    Route::get('/menu/{menu}/resep', [MenuController::class, 'getResep'])->name('menu.resep');
    Route::get('/menu/filter', [MenuController::class, 'filter'])->name('menu.filter');
    
    // ========== PRODUKSI HARIAN ==========
    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('/', [ProduksiController::class, 'index'])->name('index');
        Route::get('/create', [ProduksiController::class, 'create'])->name('create');
        Route::post('/', [ProduksiController::class, 'store'])->name('store');
        Route::get('/cek', [ProduksiController::class, 'cekKebutuhan'])->name('cek');
        Route::get('/{produksi}', [ProduksiController::class, 'show'])->name('show');
        Route::delete('/{produksi}', [ProduksiController::class, 'destroy'])->name('destroy');
        Route::put('/{produksi}/update-sisa', [ProduksiController::class, 'updateSisa'])->name('update-sisa');
        Route::post('/{produksi}/catat-kelebihan', [ProduksiController::class, 'catatKelebihan'])->name('catat-kelebihan');
    });
    
    // ========== STOK MENGENDAP ==========
    Route::prefix('stok-mengendap')->name('stok-mengendap.')->group(function () {
        Route::get('/', [StokMengendapController::class, 'index'])->name('index');
        Route::get('/{id}/gunakan', [StokMengendapController::class, 'gunakan'])->name('gunakan');
    });
    
    // ========== KEUANGAN ==========
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('index');
        Route::get('/laporan', [KeuanganController::class, 'laporan'])->name('laporan');
        Route::get('/create', [KeuanganController::class, 'create'])->name('create');
        Route::post('/', [KeuanganController::class, 'store'])->name('store');
        Route::get('/{keuangan}/edit', [KeuanganController::class, 'edit'])->name('edit');
        Route::put('/{keuangan}', [KeuanganController::class, 'update'])->name('update');
        Route::delete('/{keuangan}', [KeuanganController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [KeuanganController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [KeuanganController::class, 'exportPdf'])->name('export.pdf');
    });
    
    // ========== LAPORAN ==========
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/filter', [LaporanController::class, 'filter'])->name('filter');
        Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [LaporanController::class, 'exportPdf'])->name('export.pdf');
    });
    
    // ========== SETTING ==========
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::get('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::get('/backup', [SettingController::class, 'backup'])->name('backup');
    });
    
    // ========== SUPPLIER ==========
    Route::resource('supplier', SupplierController::class);
    
    // ========== NOTIFIKASI ==========
    Route::prefix('notification')->name('notification.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::get('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::get('/latest', [NotificationController::class, 'latest'])->name('latest');
    });
    
    // ========== USER MANAGEMENT ==========
    Route::resource('user', UserController::class);
    Route::post('/user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::post('/user/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggle-status');
    
    // ========== ACTIVITY LOG ==========
    Route::prefix('activity-log')->name('activity-log.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::delete('/clear', [ActivityLogController::class, 'clear'])->name('clear');
    });
    
    // ========== BACKUP & RESTORE ==========
    Route::prefix('backup')->name('backup.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/create', [BackupController::class, 'backup'])->name('create');
        Route::get('/download/{id}', [BackupController::class, 'download'])->name('download');
        Route::get('/delete/{id}', [BackupController::class, 'destroy'])->name('delete');
        Route::get('/restore/{id}', [BackupController::class, 'restore'])->name('restore');
    });

    // ========== DASHBOARD ==========
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/filter-7-hari', [DashboardController::class, 'filter7Hari'])->name('filter-7-hari');
    Route::get('/export-pdf', [DashboardController::class, 'exportPdf'])->name('export-pdf');
    Route::get('/export-excel', [DashboardController::class, 'exportExcel'])->name('export-excel');
    });
    // Retur Bahan
    Route::resource('retur-bahan', ReturBahanController::class);
    Route::get('/retur-bahan/{returBahan}', [ReturBahanController::class, 'show'])->name('retur-bahan.show');
    
    // ========== PROFILE ==========
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ========== THEME (DARK MODE) ==========
    Route::post('/setting/theme', [ThemeController::class, 'setTheme'])->name('setting.theme');
});