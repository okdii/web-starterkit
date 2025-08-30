<?php

namespace Database\Seeders\Tenant;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        $service = new \App\Services\Shared\ACL\PermissionService;
        $service->generatePermission();
    }
}
