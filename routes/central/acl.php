<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->group(function () {
        /**
         * ACL
         */
        // Role
        Route::resource('role', App\Http\Controllers\Central\ACL\RoleController::class)->names('role');
        // Permission
        Route::resource('permission', App\Http\Controllers\Central\ACL\PermissionController::class)->names('permission');
        // Module
        Route::resource('module', App\Http\Controllers\Central\ACL\ModuleController::class)->names('module');
        // Module - Permission
        Route::resource('module-permission', App\Http\Controllers\Central\ACL\ModulePermissionController::class)->names('module-permission');
        // Role - Module
        Route::resource('role-module', App\Http\Controllers\Central\ACL\RoleModuleController::class)->names('role-module');

        /**
         * MENU
         */
        Route::resource('menu', App\Http\Controllers\Central\Menu\MenuController::class)->names('menu');

        /**
         * AJAX
         */
        Route::group([
            'prefix' => 'ajax',
            'as' => 'ajax.',
        ], function () {

            /**
             * ACL
             */
            // Role
            Route::post('role/dt-role', [App\Http\Controllers\Central\ACL\RoleController::class, 'getRoleDt'])->name('role.dt-role');
            // Permission
            Route::post('permission/dt-permission', [App\Http\Controllers\Central\ACL\PermissionController::class, 'getPermissionDt'])->name('permission.dt-permission');
            // Module
            Route::post('module/dt-module', [App\Http\Controllers\Central\ACL\ModuleController::class, 'getModuleDt'])->name('module.dt-module');
            // Module - Permission
            Route::post('module-permission/dt-permission', [App\Http\Controllers\Central\ACL\ModulePermissionController::class, 'getPermissionDt'])->name('module-permission.dt-permission');
            // Role - Module
            Route::post('role-module/dt-module', [App\Http\Controllers\Central\ACL\RoleModuleController::class, 'getModuleDt'])->name('role-module.dt-module');

            /**
             * MENU
             */
            Route::post('menu/update-tree', [App\Http\Controllers\Central\Menu\MenuController::class, 'updateTree'])->name('menu.update-tree');
        });

    });
