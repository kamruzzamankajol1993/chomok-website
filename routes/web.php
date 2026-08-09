<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\website\AboutPageController;
use App\Http\Controllers\website\AuthController;
use App\Http\Controllers\website\BranchPageController;
use App\Http\Controllers\website\CartController;
use App\Http\Controllers\website\CheckoutController;
use App\Http\Controllers\website\ContactPageController;
use App\Http\Controllers\website\HomePageController;
use App\Http\Controllers\website\MenuPageController;
use App\Http\Controllers\website\ExtrapageController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/clear', function() {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return redirect()->back();
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::controller(HomePageController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
});

Route::controller(AboutPageController::class)->group(function () {
    Route::get('/about', 'index')->name('about.index');
});

Route::controller(MenuPageController::class)->group(function () {
    Route::get('/menu', 'index')->name('menu.index');
    Route::get('/menu/{id}', 'show')->name('menu.show');
});

Route::controller(BranchPageController::class)->group(function () {
    Route::get('/branch', 'index')->name('branch.index');
});

Route::controller(ContactPageController::class)->group(function () {
    Route::get('/contact', 'index')->name('contact.index');
    Route::post('/contact', 'store')->name('contact.store');
});

Route::controller(ExtrapageController::class)->group(function () {
    Route::get('/privacy-policy', 'privacyPolicy')->name('extra.privacy-policy');
    Route::get('/terms-and-conditions', 'termsAndConditions')->name('extra.terms-and-conditions');
    Route::get('/refund-policy', 'refundPolicy')->name('extra.refund-policy');
    Route::get('/delivery-policy', 'deliveryPolicy')->name('extra.delivery-policy');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart/add', 'addToCart')->name('cart.add');
    Route::post('/cart/update', 'updateCart')->name('cart.update');
    Route::post('/cart/remove', 'removeFromCart')->name('cart.remove');
});

Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout', 'index')->name('checkout.index');
    Route::post('/checkout/process', 'processCheckout')->name('checkout.process');
    Route::get('/checkout/success', 'checkoutSuccess')->name('checkout.success');
});


Route::middleware('guest')->group(function () {

Route::prefix('client')->name('client.')->controller(AuthController::class)->group(function () {

    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
    Route::post('/forgot-password', 'verifyEmail')->name('password.verify-email');
    Route::get('/reset-password', 'showResetForm')->name('password.reset.form');
    Route::post('/reset-password', 'reset')->name('password.update.direct');

});

});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {


 Route::controller(AuthController::class)->group(function () {

    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/view-order/{id}', 'viewOrder')->name('view-order');
    Route::post('/update-profile', 'updateProfile')->name('update-profile');

 });


});
