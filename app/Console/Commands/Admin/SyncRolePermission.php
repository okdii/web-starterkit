<?php

namespace App\Console\Commands\Admin;

use App\Models\Role;
use Illuminate\Console\Command;
use DB;

class SyncRolePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:sync-role-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync role permission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $list_role  = Role::with([
                            'relationModule', 'relationModule.relationPermission'
                        ])
                        ->get();
            foreach ($list_role as $role) {
                foreach ($role->relationModule as $module) {
                    foreach ($module->relationPermission as $permission) {
                        $role->givePermissionTo($permission);
                    }
                }
            }

            $this->info('DONE');
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
