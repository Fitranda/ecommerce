<?php

use App\Http\Controllers\Central\HomeController;
use App\Http\Controllers\Central\TenantRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central Domain Routes
|--------------------------------------------------------------------------
*/

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('central.home');

        Route::get('/register', [TenantRegistrationController::class, 'create'])->name('central.register');
        Route::post('/register', [TenantRegistrationController::class, 'store'])->name('central.register.store');
    });
}
