<?php

use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\RatingController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Halaman utama (setelah scan QR-Code)
Route::get('/', [OrderController::class, 'index'])->name('customer.home');
Route::post('/set-meja', [OrderController::class, 'setMeja'])->name('customer.setMeja');

// Menu
Route::get('/menu', [OrderController::class, 'menu'])->name('customer.menu');
Route::get('/rekomendasi', [OrderController::class, 'rekomendasi'])->name('customer.rekomendasi');

// Keranjang
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Checkout & Pembayaran
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/payment/{id_pesanan}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/cash/confirm/{id_pesanan}', [PaymentController::class, 'confirmCash'])->name('payment.confirmCash');
Route::get('/order-status/{id_pesanan}', [OrderController::class, 'status'])->name('order.status');
Route::get('/order-status-json/{id_pesanan}', [OrderController::class, 'statusJson'])->name('order.status.json');

// Rating
Route::get('/rating/{id_pesanan}', [RatingController::class, 'show'])->name('rating.show');
Route::post('/rating', [RatingController::class, 'store'])->name('rating.store');

// Webhook Midtrans
Route::post('/webhook/midtrans', [WebhookController::class, 'handleMidtrans'])
    ->name('webhook.midtrans')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
