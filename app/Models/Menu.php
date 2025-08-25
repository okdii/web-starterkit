<?php

namespace App\Models;

use App\Models\BaseModels\BaseModel;
use App\Http\Traits\CustomHashTraits;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends BaseModel
{
    use HasHashSlug, CustomHashTraits, SoftDeletes;

    protected $table = "menu";

    protected $fillable = [
        'id',
        'name',
        'icon',
        'parent_id',
        'permission_id',
        'order',
        'active',
        
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $hidden = ['id'];

    /**
     * Addition Attribute
     */
    protected $appends = [
        'slug',
        'parentSlug'
    ];

    public function getParentSlugAttribute()
    {
        return $this->relationParent?->slug();
    }

    public function relationParent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function relationChildren()
    {
        return $this->hasMany(Menu::class, 'parent_id')->with('relationChildren');
    }

    public function relationPermission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
