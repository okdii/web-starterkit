<?php

namespace App\Models\BaseModels;

use Illuminate\Pagination\LengthAwarePaginator;

class DropdownListModel extends BaseModel
{
    /**
     * Dropdown Value Field Name
     */
    protected  $dropDownFieldName = 'name';

    /**
     * Dropdown ID Field Name
     * 
     * default is $primaryKey when value is null
     */
    protected  $dropDownFieldId = null;

    /**
     * Dropdown Parent ID Field Name
     * 
     * optional
     * jika perlukan group by parent id
     */
    protected  $dropDownParentFieldIdName = null;

    /**
     * Urutan senarai
     * 
     * optional
     * default adalah $dropDownFieldName
     */
    protected $orderBy = null;

    protected $orderDirection = 'asc';

    /**
     * return dropdown list
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param mixed $parentId
     */
    public function scopeDropDownList($query, $parentId = null)
    {
        /**
         * default to primaryKey if dropDownFieldId is not set
         */
        if (is_null($this->dropDownFieldId)) {
            $this->dropDownFieldId = $this->primaryKey;
        }

        /**
         * default to primaryKey if dropDownParentFieldIdName is not set
         */
        if (is_null($this->dropDownParentFieldIdName)) {
            $this->dropDownParentFieldIdName = $this->primaryKey;
        }


        if (is_null($this->dropDownFieldName)) {
            throw new \ErrorException('Field Name can\'t be null');
        }

        /**
         * filter by parent id
         */
        if (!is_null($this->dropDownParentFieldIdName) && !is_null($parentId)) {
            $query->where($this->dropDownParentFieldIdName, $parentId);
        }

        /**
         * default to dropDownFieldName if orderBy is null
         */
        if (is_null($this->orderBy)) {
            $this->orderBy = $this->dropDownFieldName;
        }

        $query->orderBy($this->orderBy, $this->orderDirection);

        return $query;
    }

    /**
     * DEPRECATED
     * return pluck dropdown list
     * 
     * alternative to scopePluckDropdownlist
     * 
     */
    public function scopeGetDropdownlist($query, $parentId = null)
    {
        $this->scopePluckDropdownlist($query, $parentId);
    }

    /**
     * return pluck dropdown list
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param mixed $parentId
     */
    public function scopePluckDropdownlist($query, $parentId = null)
    {

        $this->scopeDropDownList($query, $parentId);

        return $query->pluck($this->dropDownFieldName, $this->dropDownFieldId);
    }

    /**
     * return paginate dropdown list
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param mixed $parentId
     * @param integer $currentPage
     * @param integer $perPage
     */
    public function scopePagingDropdownlist($query, $parentId = null, $currentPage = 1, $perPage = 10)
    {
        $options = [];
        $this->scopeDropDownList($query, $parentId);
        $total = $query->count();
        $items = $query
            ->forPage($currentPage, $perPage)
            ->pluck($this->dropDownFieldName, $this->dropDownFieldId);

        return new LengthAwarePaginator($items, $total, $perPage, $currentPage, $options);
    }


    /**
     * return select2 data dropdown list
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param mixed $parentId
     */
    public function scopeSelect2Data($query, $parentId = null, $selectedId = null)
    {

        $this->scopeDropDownList($query, $parentId);
        return $query->get()->map(function ($item, $index) use ($selectedId) {
            return [
                "id" => $item[$this->dropDownFieldId],
                "text" => $item[$this->dropDownFieldName],
                "selected" => $item[$this->dropDownFieldId] == $selectedId ? true : false
            ];
        });
    }

    public function scopeGetName($query, $id)
    {
        if (is_null($id)) {
            return null;
        }
        return $query->where($this->primaryKey, $id)->value($this->dropDownFieldName);
    }
}
