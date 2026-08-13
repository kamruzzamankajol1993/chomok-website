<?php

use App\Http\Controllers\website\AboutPageController;
use App\Http\Controllers\website\AuthController;
use App\Http\Controllers\website\BranchPageController;
use App\Http\Controllers\website\CartController;
use App\Http\Controllers\website\CheckoutController;
use App\Http\Controllers\website\ContactPageController;
use App\Http\Controllers\website\ExtrapageController;
use App\Http\Controllers\website\HomePageController;
use App\Http\Controllers\website\MenuPageController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return redirect()->back();
});

Route::get('/', [HomePageController::class, 'index'])->name('home.index');
Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('guest:client')->name('login');
Route::get('/about', [AboutPageController::class, 'index'])->name('about.index');
Route::get('/branch', [BranchPageController::class, 'index'])->name('branch.index');
Route::get('/contact', [ContactPageController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactPageController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');

Route::get('/menu', [MenuPageController::class, 'index'])->name('menu.index');
Route::get('/menu/{menuItem}', [MenuPageController::class, 'show'])->whereNumber('menuItem')->name('menu.show');
Route::get('/menu/{menuItem}/configuration', [MenuPageController::class, 'configuration'])->whereNumber('menuItem')->name('menu.configuration');

Route::get('/privacy-policy', [ExtrapageController::class, 'privacyPolicy'])->name('extra.privacy-policy');
Route::get('/terms-and-conditions', [ExtrapageController::class, 'termsAndConditions'])->name('extra.terms-and-conditions');
Route::get('/refund-policy', [ExtrapageController::class, 'refundPolicy'])->name('extra.refund-policy');
Route::get('/delivery-policy', [ExtrapageController::class, 'deliveryPolicy'])->name('extra.delivery-policy');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'addToCart'])->name('add');
    Route::post('/update', [CartController::class, 'updateCart'])->name('update');
    Route::post('/remove', [CartController::class, 'removeFromCart'])->name('remove');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('clear');
});

Route::prefix('client')->name('client.')->middleware('guest:client')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:8,1')->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:8,1')->name('register.submit');
    Route::get('/register/verify', [AuthController::class, 'showRegistrationVerificationForm'])->name('register.verify');
    Route::post('/register/verify', [AuthController::class, 'verifyRegistrationOtp'])->middleware('throttle:10,1')->name('register.verify.submit');
    Route::post('/register/resend-code', [AuthController::class, 'resendRegistrationOtp'])->middleware('throttle:5,1')->name('register.resend');
    Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'verifyEmail'])->middleware('throttle:8,1')->name('password.verify-email');
    Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [AuthController::class, 'reset'])->middleware('throttle:8,1')->name('password.update.direct');
});

Route::middleware('auth:client')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/client/dashboard', [AuthController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/client/view-order/{id}', [AuthController::class, 'viewOrder'])->whereNumber('id')->name('client.view-order');
    Route::post('/client/update-profile', [AuthController::class, 'updateProfile'])->name('client.update-profile');
    Route::get('/client/session-status', [AuthController::class, 'sessionStatus'])->name('client.session-status');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'checkoutSuccess'])->name('checkout.success');
});
