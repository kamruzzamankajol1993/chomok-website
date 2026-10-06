<?php

use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\AboutPageContentController;
use App\Http\Controllers\Admin\ContactPageContentController;
use App\Http\Controllers\Admin\ContactQueryController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShopPageContentController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WebsiteContentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $routes = [
        'dashboard.view' => 'admin.dashboard',
        'category.view' => 'admin.categories.index',
        'subcategory.view' => 'admin.subcategories.index',
        'menu-item.view' => 'admin.menu-items.index',
        'addon.view' => 'admin.addons.index',
        'order.view' => 'admin.orders.index',
        'client.view' => 'admin.clients.index',
        'branch.view' => 'admin.branches.index',
        'user.view' => 'admin.users.index',
        'role.view' => 'admin.roles.index',
        'permission.view' => 'admin.permissions.index',
        'website-content.view' => 'admin.website.home.index',
        'contact-query.view' => 'admin.contact-queries.index',
        'setting.view' => 'admin.settings.edit',
    ];

    foreach ($routes as $permission => $routeName) {
        if (auth()->user()->can($permission)) {
            return redirect()->route($routeName);
        }
    }

    return redirect()->route('admin.profile.edit');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'verifyEmail'])->middleware('throttle:5,1')->name('password.verify-email');
    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->middleware('throttle:5,1')->name('password.update.direct');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'active.user'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->middleware('permission:dashboard.view')->name('dashboard');
    Route::get('/search', [HomeController::class, 'search'])->name('search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', fn () => redirect()->route('admin.profile.edit'));

    Route::get('/categories', [CategoryController::class, 'index'])->middleware('permission:category.view')->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('permission:category.create')->name('categories.store');
    Route::post('/categories/bulk-action', [CategoryController::class, 'bulkAction'])->middleware('permission:category.edit|category.delete')->name('categories.bulk-action');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('permission:category.edit')->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('permission:category.delete')->name('categories.destroy');

    Route::get('/subcategories', [SubcategoryController::class, 'index'])->middleware('permission:subcategory.view')->name('subcategories.index');
    Route::post('/subcategories', [SubcategoryController::class, 'store'])->middleware('permission:subcategory.create')->name('subcategories.store');
    Route::post('/subcategories/bulk-action', [SubcategoryController::class, 'bulkAction'])->middleware('permission:subcategory.edit|subcategory.delete')->name('subcategories.bulk-action');
    Route::put('/subcategories/{subcategory}', [SubcategoryController::class, 'update'])->middleware('permission:subcategory.edit')->name('subcategories.update');
    Route::delete('/subcategories/{subcategory}', [SubcategoryController::class, 'destroy'])->middleware('permission:subcategory.delete')->name('subcategories.destroy');

    Route::get('/menu-items', [MenuItemController::class, 'index'])->middleware('permission:menu-item.view')->name('menu-items.index');
    Route::get('/menu-items/create', [MenuItemController::class, 'create'])->middleware('permission:menu-item.create')->name('menu-items.create');
    Route::post('/menu-items', [MenuItemController::class, 'store'])->middleware('permission:menu-item.create')->name('menu-items.store');
    Route::post('/menu-items/bulk-action', [MenuItemController::class, 'bulkAction'])->middleware('permission:menu-item.edit|menu-item.delete')->name('menu-items.bulk-action');
    Route::get('/menu-items/{menuItem}', [MenuItemController::class, 'show'])->middleware('permission:menu-item.view')->name('menu-items.show');
    Route::get('/menu-items/{menuItem}/edit', [MenuItemController::class, 'edit'])->middleware('permission:menu-item.edit')->name('menu-items.edit');
    Route::put('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->middleware('permission:menu-item.edit')->name('menu-items.update');
    Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->middleware('permission:menu-item.delete')->name('menu-items.destroy');

    Route::get('/addons', [AddonController::class, 'index'])->middleware('permission:addon.view')->name('addons.index');
    Route::post('/addons', [AddonController::class, 'store'])->middleware('permission:addon.create')->name('addons.store');
    Route::post('/addons/bulk-action', [AddonController::class, 'bulkAction'])->middleware('permission:addon.edit|addon.delete')->name('addons.bulk-action');
    Route::put('/addons/{addon}', [AddonController::class, 'update'])->middleware('permission:addon.edit')->name('addons.update');
    Route::delete('/addons/{addon}', [AddonController::class, 'destroy'])->middleware('permission:addon.delete')->name('addons.destroy');


    Route::get('/orders/export', [OrderController::class, 'export'])->middleware('permission:order.view')->name('orders.export');
    Route::get('/orders/pos/menu-items', [OrderController::class, 'menuItems'])->middleware('permission:order.create|order.edit')->name('orders.pos.menu-items');
    Route::get('/orders/clients/search', [OrderController::class, 'clientSearch'])->middleware('permission:order.create|order.edit')->name('orders.clients.search');
    Route::post('/orders/clients/quick-store', [ClientController::class, 'quickStore'])->middleware('permission:order.create|order.edit')->name('orders.clients.quick-store');
    Route::get('/orders/pending-notifications', [OrderController::class, 'pendingNotifications'])->middleware('permission:order.view')->name('orders.pending-notifications');
    Route::post('/orders/{order}/notification-seen', [OrderController::class, 'markNotificationSeen'])->middleware('permission:order.view')->name('orders.notification-seen');
    Route::post('/orders/{order}/notification-dismiss', [OrderController::class, 'dismissNotification'])->middleware('permission:order.view')->name('orders.notification-dismiss');
    Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm'])->middleware('permission:order.edit')->name('orders.confirm');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('permission:order.edit')->name('orders.status');
    Route::get('/orders/{order}/invoice/a4', [OrderController::class, 'invoiceA4'])->middleware('permission:order.view')->name('orders.invoice.a4');
    Route::get('/orders/{order}/invoice/80mm', [OrderController::class, 'invoice80mm'])->middleware('permission:order.view')->name('orders.invoice.80mm');
    Route::get('/orders', [OrderController::class, 'index'])->middleware('permission:order.view')->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->middleware('permission:order.create')->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->middleware('permission:order.create')->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('permission:order.view')->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->middleware('permission:order.edit')->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->middleware('permission:order.edit')->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->middleware('permission:order.delete')->name('orders.destroy');

    Route::get('/clients/export', [ClientController::class, 'export'])->middleware('permission:client.view')->name('clients.export');
    Route::put('/clients/{client}/notes', [ClientController::class, 'updateNotes'])->middleware('permission:client.edit')->name('clients.notes');
    Route::patch('/clients/{client}/status', [ClientController::class, 'toggleStatus'])->middleware('permission:client.edit')->name('clients.status');
    Route::get('/clients', [ClientController::class, 'index'])->middleware('permission:client.view')->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->middleware('permission:client.create')->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->middleware('permission:client.create')->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->middleware('permission:client.view')->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->middleware('permission:client.edit')->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->middleware('permission:client.edit')->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->middleware('permission:client.delete')->name('clients.destroy');

    Route::get('/branches', [BranchController::class, 'index'])->middleware('permission:branch.view')->name('branches.index');
    Route::get('/branches/create', [BranchController::class, 'create'])->middleware('permission:branch.create')->name('branches.create');
    Route::post('/branches', [BranchController::class, 'store'])->middleware('permission:branch.create')->name('branches.store');
    Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->middleware('permission:branch.edit')->name('branches.edit');
    Route::put('/branches/{branch}', [BranchController::class, 'update'])->middleware('permission:branch.edit')->name('branches.update');
    Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->middleware('permission:branch.delete')->name('branches.destroy');

    Route::get('/users', [UserController::class, 'index'])->middleware('permission:user.view')->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->middleware('permission:user.create')->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:user.create')->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:user.view')->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:user.edit')->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:user.edit')->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:user.delete')->name('users.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:role.view')->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:role.create')->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:role.create')->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:role.edit')->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:role.edit')->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:role.delete')->name('roles.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permission.view')->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->middleware('permission:permission.create')->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->middleware('permission:permission.create')->name('permissions.store');
    Route::get('/permissions/group/{group}/edit', [PermissionController::class, 'editGroup'])->middleware('permission:permission.edit')->where('group', '.*')->name('permissions.edit-group');
    Route::put('/permissions/group/{group}', [PermissionController::class, 'updateGroup'])->middleware('permission:permission.edit')->where('group', '.*')->name('permissions.update-group');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:permission.delete')->name('permissions.destroy');


    Route::get('/website/homepage', [HomepageContentController::class, 'index'])->middleware('permission:website-content.view')->name('website.home.index');
    Route::put('/website/homepage/about', [HomepageContentController::class, 'updateAbout'])->middleware('permission:website-content.edit')->name('website.home.about.update');
    Route::put('/website/homepage/hungry', [HomepageContentController::class, 'updateHungry'])->middleware('permission:website-content.edit')->name('website.home.hungry.update');
    Route::post('/website/homepage/slides', [HomepageContentController::class, 'storeSlide'])->middleware('permission:website-content.edit')->name('website.home.slides.store');
    Route::put('/website/homepage/slides/{slide}', [HomepageContentController::class, 'updateSlide'])->middleware('permission:website-content.edit')->name('website.home.slides.update');
    Route::delete('/website/homepage/slides/{slide}', [HomepageContentController::class, 'destroySlide'])->middleware('permission:website-content.edit')->name('website.home.slides.destroy');
    Route::put('/website/homepage/promos/{promo}', [HomepageContentController::class, 'updatePromo'])->middleware('permission:website-content.edit')->name('website.home.promos.update');

    Route::get('/website/about-page', [AboutPageContentController::class, 'edit'])->middleware('permission:website-content.view')->name('website.about.edit');
    Route::put('/website/about-page', [AboutPageContentController::class, 'update'])->middleware('permission:website-content.edit')->name('website.about.update');
    Route::post('/website/about-page/features', [AboutPageContentController::class, 'storeFeature'])->middleware('permission:website-content.edit')->name('website.about.features.store');
    Route::put('/website/about-page/features/{feature}', [AboutPageContentController::class, 'updateFeature'])->middleware('permission:website-content.edit')->name('website.about.features.update');
    Route::delete('/website/about-page/features/{feature}', [AboutPageContentController::class, 'destroyFeature'])->middleware('permission:website-content.edit')->name('website.about.features.destroy');

    Route::get('/website/contact-page', [ContactPageContentController::class, 'edit'])->middleware('permission:website-content.view')->name('website.contact.edit');
    Route::put('/website/contact-page', [ContactPageContentController::class, 'update'])->middleware('permission:website-content.edit')->name('website.contact.update');

    Route::get('/website/shop-page', [ShopPageContentController::class, 'edit'])->middleware('permission:website-content.view')->name('website.shop.edit');
    Route::put('/website/shop-page', [ShopPageContentController::class, 'update'])->middleware('permission:website-content.edit')->name('website.shop.update');

    Route::get('/website/content', [WebsiteContentController::class, 'edit'])->middleware('permission:website-content.view')->name('website.content.edit');
    Route::put('/website/content', [WebsiteContentController::class, 'update'])->middleware('permission:website-content.edit')->name('website.content.update');

    Route::get('/contact-queries', [ContactQueryController::class, 'index'])->middleware('permission:contact-query.view')->name('contact-queries.index');
    Route::post('/contact-queries/bulk-action', [ContactQueryController::class, 'bulkAction'])->middleware('permission:contact-query.edit|contact-query.delete')->name('contact-queries.bulk-action');
    Route::put('/contact-queries/{contactQuery}', [ContactQueryController::class, 'update'])->middleware('permission:contact-query.edit')->name('contact-queries.update');
    Route::delete('/contact-queries/{contactQuery}', [ContactQueryController::class, 'destroy'])->middleware('permission:contact-query.delete')->name('contact-queries.destroy');

    Route::get('/settings', [SettingController::class, 'edit'])->middleware('permission:setting.view')->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->middleware('permission:setting.edit')->name('settings.update');

    Route::post('/clear-cache', [SystemController::class, 'clearCache'])->middleware('permission:setting.edit')->name('clear-cache');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::post('/push-subscription', [\App\Http\Controllers\Admin\PushSubscriptionController::class, 'store'])->name('admin.push.subscription');
});
