<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;
use Spatie\Permission\Models\Role AS SpatieRole;
use OwenIt\Auditing\Contracts\Auditable;

class Role extends SpatieRole implements Auditable
{
    use HasHashSlug, CustomHashTraits;
    use \OwenIt\Auditing\Auditable;

    protected $table = "roles";

    protected $fillable = [
        'id',
        'name',
        'guard_name',

        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $hidden = ['id'];

    /**
     * Addition Attribute
     */
    protected $appends = [
        'slug'
    ];

    /**
     * Sync permission for user based on role
     */
    public static function syncRole($user) 
    {
        // Sync Role
        $rolesArr   = Role::get()->pluck('name', 'id')->toArray();
        $userRoleArr = [];
        foreach($user->role as $userrole) {
            $userRoleArr[] = $rolesArr[$userrole];
        }
        $user->syncRoles($userRoleArr);
    }

    /**
     * Sync permission by module
     */
    public static function syncPermissionByModuleId($module_id) 
    {
        $list_role  = Role::whereHas('relationModule', function($query) use($module_id) {
                        $query->where('modules.id', $module_id);
                    })
                    ->with([
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
    }

    /**
     * Sync permission by role
     */
    public static function syncPermissionByRoleId($role_id) 
    {
        $list_role  = Role::with([
                        'relationModule', 'relationModule.relationPermission'
                    ])
                    ->where('id', $role_id)
                    ->first();
        foreach ($list_role->relationModule as $module) {
            foreach ($module->relationPermission as $permission) {
                $list_role->givePermissionTo($permission);
            }
        }
    }

    /**
     * Relation
     */
    public function relationRoleModule()
    {
        return $this->hasMany(RoleModule::class, 'role_id');
    }

    public function relationModule()
    {
        return $this->belongsToMany(Module::class, RoleModule::class);
    }
}
