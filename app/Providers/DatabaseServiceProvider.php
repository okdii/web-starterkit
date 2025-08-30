<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blueprint::macro('DBExecutors', function ($precision = 0) {
            $this
                ->foreignIdFor(\App\Models\User::class, 'created_by')
                ->nullable();

            $this
                ->foreignIdFor(\App\Models\User::class, 'updated_by')
                ->nullable();

            #$this->timestamps($precision);

            $this
                ->timestamp('created_at', $precision)
                ->default(\DB::raw('CURRENT_TIMESTAMP'));

            $this->timestamp('updated_at', $precision)->nullable();
        });

        Blueprint::macro('DBCreateOnly', function ($precision = 0) {
            $this
                ->foreignIdFor(\App\Models\User::class, 'created_by')
                ->nullable();

            $this
                ->timestamp('created_at', $precision)
                ->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        Blueprint::macro('DBSoftDeletes', function ($column = 'deleted_at', $precision = 0) {
            $this
                ->foreignIdFor(\App\Models\User::class, 'deleted_by')
                ->nullable();

            $this->softDeletes($column, $precision)->nullable();
        });
    }
}
