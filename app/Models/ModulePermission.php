<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ModulePermission extends BaseModel implements Auditable
{
    use HasHashSlug, CustomHashTraits, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "module_permissions";

    protected $fillable = [
        'id',
        'permission_id',
        'module_id',

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
    public function relationPermission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
    
    public function relationModule()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
