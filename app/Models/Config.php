<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;

class Config extends BaseModel
{
    use HasHashSlug, CustomHashTraits;

    protected $table = "configs";

    protected $fillable = [
        'id',
        'item',
        'value',
        'remark',

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
}
