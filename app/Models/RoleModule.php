<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;
use OwenIt\Auditing\Contracts\Auditable;

class RoleModule extends BaseModel implements Auditable
{
    use HasHashSlug, CustomHashTraits;
    use \OwenIt\Auditing\Auditable;

    protected $table = "role_modules";

    protected $fillable = [
        'id',
        'module_id',
        'role_id',

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
    public function relationModule()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
    
    public function relationRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
