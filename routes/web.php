<?php

use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientApiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;

// Home page
Route::get('/', [IndexController::class, 'index'])->name('home');

// Order Print
Route::get('/order/print/{order}', [OrderController::class, 'print'])->name('order.print');

// Theme routes
Route::get('category/{category:slug}', [ThemeController::class, 'show'])->name('themes.show');

// Artwork routes
Route::get('products', [ArtworkController::class, 'index'])->name('products.index');
Route::get('products/{product:slug}', [ArtworkController::class, 'show'])->name('products.show');

// Cart routes
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');

// Address routes (for both guests and authenticated users)
Route::get('cart/addresses', [CartController::class, 'addresses'])->name('cart.addresses');
Route::post('cart/addresses', [CartController::class, 'storeAddress'])->name('cart.addresses.store');
Route::put('cart/addresses/{id}', [CartController::class, 'updateAddress'])->name('cart.addresses.update');
Route::delete('cart/addresses/{id}', [CartController::class, 'deleteAddress'])->name('cart.addresses.delete');
Route::post('cart/addresses/{id}/default', [CartController::class, 'setDefaultAddress'])->name('cart.addresses.default');

// Checkout routes
Route::controller(CheckoutController::class)->group(function () {
    Route::post('/checkout', 'process')->name('artworks.checkout.process');
    Route::post('/order/process', 'orderProcess')->name('artworks.order.process');
    Route::get('/checkout/{token}', 'index')->name('artworks.checkout');
    Route::post('/coupon/apply', 'applyCoupon')->name('artworks.coupon.apply');
    Route::post('/coupon/remove', 'removeCoupon')->name('artworks.coupon.remove');
});

// Subscriptions
Route::get('/subscribe/plan', [SubscribeController::class, 'plan'])->name('subscribe.plan');

// Subscription Management
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/subscribe/{plan}', [SubscribeController::class, 'subscribe'])->name('subscribe.process');
    Route::post('/subscribe/{plan}/renew', [SubscribeController::class, 'renew'])->name('subscription.renew');
    Route::post('/subscribe/{plan}/cancel', [SubscribeController::class, 'cancel'])->name('subscription.cancel');
    Route::get('/subscribe/manage', [SubscribeController::class, 'manage'])->name('subscription.manage');
});

// Auth routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [IndexController::class, 'dashboard'])->name('dashboard');

    // Account Management
    Route::get('account', [AccountController::class, 'index'])->name('account');
    Route::get('account/subscription', [AccountController::class, 'subscription'])->name('account.subscription');
    Route::get('account/security', [AccountController::class, 'security'])->name('account.security');
    Route::post('account/update', [AccountController::class, 'updateProfile'])->name('user.update');
    Route::post('account/password', [AccountController::class, 'updatePassword'])->name('user.password.update');

    // Account Addresses
    Route::get('account/addresses', [AccountController::class, 'addresses'])->name('account.addresses');
    Route::post('account/addresses', [AccountController::class, 'storeAddress'])->name('account.addresses.store');
    Route::put('account/addresses/{id}', [AccountController::class, 'updateAddress'])->name('account.addresses.update');
    Route::delete('account/addresses/{id}', [AccountController::class, 'deleteAddress'])->name('account.addresses.delete');
    Route::post('account/addresses/{id}/default', [AccountController::class, 'setDefaultAddress'])->name('account.addresses.default');
});

//client api use to make some change
Route::prefix('client')->middleware('auth')->group(function () {
    Route::post('address/store', [ClientApiController::class, 'addressStore'])->name('client.address.store');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

// Route::impersonate();
