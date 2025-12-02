<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminController;

// === Главная страница ===
Route::get('/', function () {
    return view('welcome');
})->name('home');

// === Авторизация ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// === Dashboard для клиентов ===
Route::get('/dashboard', [App\Http\Controllers\CatalogController::class, 'index'])->middleware('auth')->name('dashboard');

// === Страница товара (доступна всем) ===
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');

// === Корзина ===
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/step1', [OrderController::class, 'step1'])->name('checkout.step1');
    Route::post('/checkout/step2', [OrderController::class, 'step2'])->name('checkout.step2');
    Route::post('/checkout/step3', [OrderController::class, 'step3'])->name('checkout.step3');
    Route::post('/order/create', [OrderController::class, 'store'])->name('order.create');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
});

// === Админка ===
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Заказы
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'orderDetail'])->name('order.detail');
    Route::post('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    
    // Товары
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::post('/products/{id}/images/{imageId}/delete', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.delete');
    
    // Категории
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    
    // Пользователи
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Интеграция с 1С
    Route::get('/onec', [App\Http\Controllers\Admin\OneCController::class, 'index'])->name('onec.index');
    Route::post('/onec/export/{order}', [App\Http\Controllers\Admin\OneCController::class, 'exportOrder'])->name('onec.export');
    Route::post('/onec/export-all', [App\Http\Controllers\Admin\OneCController::class, 'exportAll'])->name('onec.export-all');
});