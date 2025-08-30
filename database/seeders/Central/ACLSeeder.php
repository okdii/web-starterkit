<?php

namespace Database\Seeders\Central;

use Illuminate\Database\Seeder;

class ACLSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Database\Seeders\Central\PermissionSeeder::class,
            \Database\Seeders\Central\RoleSeeder::class,
            \Database\Seeders\Central\ModuleSeeder::class,
            \Database\Seeders\Central\ModulePermissionSeeder::class,
            \Database\Seeders\Central\RoleModuleSeeder::class,
        ]);
    }
}
