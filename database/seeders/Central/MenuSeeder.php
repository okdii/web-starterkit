<?php

namespace Database\Seeders\Central;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Menu::truncate();
        Schema::enableForeignKeyConstraints();

        $menus = [
            [
                'id'            => 1,
                'name'          => 'Dashboard',
                'icon'          => 'pi pi-home',
                'parent_id'     => null,
                'permission_id' => \App\Models\Permission::where('name', 'central.dashboard')->first()->id,
                'order'         => 0,
            ],
            [
                'id'            => 2,
                'name'          => 'Users',
                'icon'          => 'pi pi-users',
                'parent_id'     => null,
                'permission_id' => \App\Models\Permission::where('name', 'central.user.index')->first()->id,
                'order'         => 0,
            ],
            [
                'id'            => 3,
                'name'          => 'Access Level',
                'icon'          => 'pi pi-lock',
                'parent_id'     => null,
                'permission_id' => null,
                'order'         => 0,
            ],
            [
                'id'            => 4,
                'name'          => 'Modules',
                'icon'          => 'pi pi-folder',
                'parent_id'     => 3,
                'permission_id' => \App\Models\Permission::where('name', 'central.module.index')->first()->id,
                'order'         => 1,
            ],
            [
                'id'            => 5,
                'name'          => 'Permissions',
                'icon'          => 'pi pi-list',
                'parent_id'     => 3,
                'permission_id' => \App\Models\Permission::where('name', 'central.permission.index')->first()->id,
                'order'         => 1,
            ],
            [
                'id'            => 6,
                'name'          => 'Roles',
                'icon'          => 'pi pi-user',
                'parent_id'     => 3,
                'permission_id' => \App\Models\Permission::where('name', 'central.role.index')->first()->id,
                'order'         => 1,
            ],
            [
                'id'            => 7,
                'name'          => 'Menu',
                'icon'          => 'pi pi-align-left',
                'parent_id'     => null,
                'permission_id' => \App\Models\Permission::where('name', 'central.menu.index')->first()->id,
                'order'         => 0,
            ],
            [
                'id'            => 8,
                'name'          => 'Tenant',
                'icon'          => 'pi pi-id-card',
                'parent_id'     => null,
                'permission_id' => \App\Models\Permission::where('name', 'central.tenant.index')->first()->id,
                'order'         => 0,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate([
                'id'            => $menu['id'],
                'name'          => $menu['name'],
                'icon'          => $menu['icon'],
                'parent_id'     => $menu['parent_id'],
                'permission_id' => $menu['permission_id'],
                'order'         => $menu['order'],
            ]);
        }
    }
}
