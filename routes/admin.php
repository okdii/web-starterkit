<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

/**
 * USER
 */
Route::resource('user', App\Http\Controllers\Admin\User\UserController::class)->names('user');

/**
 * AJAX
 */
Route::group([
        'prefix'     => 'ajax',
        'as'         => 'ajax.',
    ], function () {

    Route::get('user/dt-user', [\App\Http\Controllers\Admin\User\UserController::class, 'getUserDt'])
        ->name('user.dt-user');
});
