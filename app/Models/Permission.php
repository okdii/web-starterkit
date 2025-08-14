<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;
use Spatie\Permission\Models\Permission AS SpatiePermission;

class Permission extends SpatiePermission
{
    use HasHashSlug, CustomHashTraits;

    protected $table = "permissions";

    protected $fillable = [
        'id',
        'name',
        'guard_name',
        'method',
        'url',
        'function',
        'hash',

        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * Addition Attribute
     */
    protected $appends = [
        'slug'
    ];

    /**
     * Relation
     */
    public function relationModulePermission()
    {
        return $this->hasMany(ModulePermission::class);
    }
}
