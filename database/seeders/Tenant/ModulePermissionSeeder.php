<?php

namespace Database\Seeders\Tenant;

use App\Models\Module;
use App\Models\ModulePermission;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $module = Module::where('name', 'Tenant Module')->first();
        $permissions = Permission::orderBy('id')->get();

        foreach ($permissions as $permission) {
            ModulePermission::firstOrCreate([
                'module_id' => $module->id,
                'permission_id' => $permission->id,
            ]);
        }

        Role::syncPermissionByModuleId($module->id);
    }
}
