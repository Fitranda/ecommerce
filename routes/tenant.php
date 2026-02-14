<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Admin\DashboardController;
use App\Http\Controllers\Tenant\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Tenant\Auth\AuthController;
use App\Http\Controllers\Tenant\CartController;
use App\Http\Controllers\Tenant\CategoryController;
use App\Http\Controllers\Tenant\CheckoutController;
use App\Http\Controllers\Tenant\OrderController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\StorefrontController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'web',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // ── Storefront (public) ──
    Route::get('/', [StorefrontController::class, 'home'])->name('tenant.home');
    Route::get('/products', [StorefrontController::class, 'products'])->name('tenant.products');
    Route::get('/products/{slug}', [StorefrontController::class, 'productDetail'])->name('tenant.products.detail');

    // ── Auth ──
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('tenant.login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegister'])->name('tenant.register');
        Route::post('/register', [AuthController::class, 'register']);
    });
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('tenant.logout');

    // ── Cart (public, works for guests too) ──
    Route::get('/cart', [CartController::class, 'index'])->name('tenant.cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('tenant.cart.store');
    Route::put('/cart/{item}', [CartController::class, 'update'])->name('tenant.cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('tenant.cart.destroy');

    // ── Authenticated Customer Routes ──
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('tenant.checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('tenant.checkout.store');
        Route::get('/orders', [OrderController::class, 'index'])->name('tenant.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('tenant.orders.show');
    });

    // ── Admin Routes ──
    Route::middleware(['auth', EnsureUserIsAdmin::class])
        ->prefix('admin')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.admin.dashboard');

            Route::resource('products', ProductController::class)
                ->names('tenant.admin.products');

            Route::resource('categories', CategoryController::class)
                ->except('show')
                ->names('tenant.admin.categories');

            Route::get('/orders', [AdminOrderController::class, 'index'])->name('tenant.admin.orders.index');
            Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('tenant.admin.orders.show');
            Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('tenant.admin.orders.status');
        });
});
