<?php

namespace Database\Seeders\Central;

use App\Models\Module;
use App\Models\RoleModule;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleModuleSeeder extends Seeder
{
    public function run(): void
    {
        $module = Module::where('name', 'Central Module')->first();
        $admin  = Role::where('name', 'Super Admin')->first();

        RoleModule::firstOrCreate([
            'role_id'   => $admin->id,
            'module_id' => $module->id,
        ]);

        Role::syncPermissionByRoleId($admin->id);
    }
}
