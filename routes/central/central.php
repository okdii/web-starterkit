<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', [\App\Http\Controllers\Central\Dashboard\DashboardController::class, 'index'])->name('dashboard');

/**
 * USER
 */
Route::resource('user', App\Http\Controllers\Central\User\UserController::class)->names('user');

/**
 * TENANT
 */
Route::resource('tenant', App\Http\Controllers\Central\Tenant\TenantController::class)->names('tenant');

/**
 * AJAX
 */
Route::group([
        'prefix'     => 'ajax',
        'as'         => 'ajax.',
    ], function () {
    
    /**
     * USER
     */
    Route::post('user/dt-user', [\App\Http\Controllers\Central\User\UserController::class, 'getUserDt'])
        ->name('user.dt-user');

    /**
     * TENANT
     */
    Route::post('tenant/dt-tenant', [\App\Http\Controllers\Central\Tenant\TenantController::class, 'getTenantDt'])
        ->name('tenant.dt-tenant');
});
