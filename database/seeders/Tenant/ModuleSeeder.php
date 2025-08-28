<?php

namespace Database\Seeders\Tenant;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Module::truncate();
        Schema::enableForeignKeyConstraints();

        $modules = [
            'Tenant Module'
        ];

        foreach ($modules as $module) {
            Module::firstOrCreate([
                'name'          => $module,
                'description'   => $module
            ]);
        }
    }
}
