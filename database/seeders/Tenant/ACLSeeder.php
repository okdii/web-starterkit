<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;

class ACLSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Database\Seeders\Tenant\PermissionSeeder::class,
            \Database\Seeders\Tenant\RoleSeeder::class,
            \Database\Seeders\Tenant\ModuleSeeder::class,
            \Database\Seeders\Tenant\ModulePermissionSeeder::class,
            \Database\Seeders\Tenant\RoleModuleSeeder::class,
        ]);
    }
}
