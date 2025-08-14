<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Audit extends BaseModel
{
    use HasHashSlug, CustomHashTraits;

    protected $table = "audits";

    protected $fillable = [
        "id",
        "user_type",
        "user_id",
        "event",
        "auditable_type",
        "auditable_id",
        "action",
        "old_values",
        "new_values",
        "url",
        "ip_address",
        "user_agent",
        "tags",

        'created_at',
        'updated_at',
    ];

    /**
     * Addition Attribute
     */
    protected $appends = [
        'slug'
    ];
}
