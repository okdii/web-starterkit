<?php

namespace Database\Seeders\Tenant;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        User::factory()->create([
            'name'  => 'Tenant Admin',
            'email' => 'tenant_admin@local',
        ]);

        User::factory(10)->create();

        \Artisan::call('admin:sync-user-role');
    }
}
