<?php

namespace App\Http\Traits;

trait CustomHashTraits
{
    /**
     * Addition Attribute
     */
    /* public function getAppendsField($append = [])
    {
        return ['Slug'];
        // return $append[] = 'Slug';
    } */
    /* protected $appends = array_merge($this->$appends, [
        'Slug'
    ]); */

    public function getSlugAttribute()
    {
        return $this->slug();
    }
}
