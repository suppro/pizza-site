<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderAdminController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// === Авторизация ===
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// === Авторизация ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    
    // Регистрация
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

// === Dashboard для авторизованных ===
Route::get('/dashboard', function () {
    // Простая проверка сессии
    if (!session('user_id')) {
        return redirect()->route('login');
    }
    return view('dashboard');
})->name('dashboard');

// === Корзина и заказы ===
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order/create', [OrderController::class, 'store'])->name('order.create');
Route::get('/orders', [OrderController::class, 'index'])->name('orders');

// === Админка ===
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders');
    Route::post('/orders/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.status');
});

