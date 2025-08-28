<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::get('/testing', function () {
    /* dd([
        'tenant' => tenancy()->tenant,
        'db' => \DB::connection()->getDatabaseName(),
    ]); */
    dd(\App\Models\User::all());
    return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
});

Route::get('dashboard', [\App\Http\Controllers\Tenant\Dashboard\DashboardController::class, 'index'])
    ->name('dashboard');

/**
 * USER
 */
Route::resource('user', App\Http\Controllers\Tenant\User\UserController::class)->names('user');


/**
 * AJAX
 */
Route::group([
    'prefix' => 'ajax',
    'as' => 'ajax.',
], function () {

    /**
     * USER
     */
    Route::post('user/dt-user', [\App\Http\Controllers\Tenant\User\UserController::class, 'getUserDt'])
        ->name('user.dt-user');

});
