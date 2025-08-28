<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            $centralDomains = config('tenancy.central_domains');

            foreach ($centralDomains as $domain) {
                Route::middleware(['web', 'auth', 'verified'])
                    ->domain($domain)
                    ->prefix('central')
                    ->as('central.')
                    ->group([
                        base_path('routes/central/acl.php'),
                        base_path('routes/central/central.php'),
                    ]);

                Route::middleware(['web'])
                    ->domain($domain)
                    ->prefix('central')
                    ->as('central.')
                    ->group([
                        base_path('routes/central/web.php'),
                    ]);
            }

            Route::middleware([
                    'web',
                    InitializeTenancyByDomainOrSubdomain::class,
                    PreventAccessFromCentralDomains::class,
                    'auth', 'verified'
                ])
                ->as('tenant.')
                ->group([
                    base_path('routes/tenant/acl.php'),
                    base_path('routes/tenant/tenant.php'),
                ]);

            Route::middleware([
                    'web',
                    InitializeTenancyByDomainOrSubdomain::class,
                    PreventAccessFromCentralDomains::class,
                ])
                ->as('tenant.')
                ->group([
                    base_path('routes/tenant/web.php'),
                ]);
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
            ->web(append: [
                \App\Http\Middleware\HandleInertiaRequests::class,
                \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            ])
            ->redirectGuestsTo(fn () => (tenancy()->initialized) ? route('tenant.login') : route('central.login'));

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
