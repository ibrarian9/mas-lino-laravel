<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\QRCodeController;
use App\Http\Controllers\Admin\SAWReportController;
use App\Http\Controllers\Admin\SalesReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Pesanan
        Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/pesanan-baru', [OrderManagementController::class, 'pesananBaru'])->name('orders.pesananBaru');
        Route::get('/orders/{id}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/validate-cash', [OrderManagementController::class, 'validateCash'])->name('orders.validateCash');
        Route::post('/orders/{id}/update-status', [OrderManagementController::class, 'updateStatus'])->name('orders.updateStatus');

        // Manajemen Menu
        Route::resource('menu', MenuController::class);
        Route::post('/menu/{id}/toggle-active', [MenuController::class, 'toggleActive'])->name('menu.toggleActive');

        // === Hanya Admin Manajemen ===
        Route::middleware('admin.manajemen')->group(function () {
            // Reset Data
            Route::post('/reset-data', [DashboardController::class, 'resetData'])->name('resetData');

            // QR Code
            Route::get('/qrcode', [QRCodeController::class, 'generate'])->name('qrcode');

            // SAW Report
            Route::get('/saw-report', [SAWReportController::class, 'index'])->name('saw.index');
            Route::get('/saw-report/export', [SAWReportController::class, 'exportExcel'])->name('saw.export');

            // Sales Report
            Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales.index');
            Route::get('/sales-report/export', [SalesReportController::class, 'exportExcel'])->name('sales.export');
        });
    });
});
