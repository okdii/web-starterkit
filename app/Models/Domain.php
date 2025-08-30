<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Domain extends BaseModel implements Auditable
{
    use HasHashSlug, CustomHashTraits, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "domains";

    protected $fillable = [
        'id',
        'domain',
        'tenant_id',

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
    public function relationTenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
