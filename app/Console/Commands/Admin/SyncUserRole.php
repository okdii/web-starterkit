<?php

namespace App\Console\Commands\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use DB;

class SyncUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:sync-user-role {--userid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $userid     = $this->option('userid');
            $list_user  = User::whereNotNull('role')
                            ->when(isset($userid), function($query) use($userid) {
                                $query->where('id', $userid);
                            })
                            ->get();
            foreach ($list_user as $user) {
                Role::syncRole($user);
            }

            $this->info('DONE');
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
