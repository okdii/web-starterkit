<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;

class Menu extends BaseModel
{
    use HasHashSlug, CustomHashTraits;

    protected $table = "menu";

    protected $fillable = [
        'id',
        'name',
        'icon',
        'parent',
        'url',
        'header',
        'permission_id',
        'sort',
        'active',
        
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
}
