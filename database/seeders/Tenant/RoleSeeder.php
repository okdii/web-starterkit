<?php

namespace Database\Seeders\Tenant;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();

        $roles = ['Super Admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name'          => $role,
                'guard_name'    => 'web',
            ]);
        }
    }
}
