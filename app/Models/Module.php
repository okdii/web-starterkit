<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Module extends BaseModel implements Auditable
{
    use HasHashSlug, CustomHashTraits, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "modules";

    protected $fillable = [
        'id',
        'name',
        'description',

        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
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
        return $this->hasMany(ModulePermission::class, 'module_id');
    }

    public function relationPermission()
    {
        return $this->belongsToMany(Permission::class, ModulePermission::class);
    }
}
