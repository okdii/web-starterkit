<?php

namespace App\Services\Shared\Datatable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DataTableService
{
    protected Request $request;
    protected Builder $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function for(Builder $query)
    {
        $this->query = $query;
        return $this;
    }

    public function search(array $columns)
    {
        if ($search = $this->request->input('keyword')) {
            $this->query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        return $this;
    }

    /**
     * Summary of filter
     * @param array $filters
     * @return static
     * 
     * // Simple field (column = value)
        'status' => '=',

        // Custom request key mapped to DB field
        'user_id' => [
            'field' => 'customer_id',
            'operator' => '=',
            'request_key' => 'customer',
        ],

        // Relationship filter
        'user_role' => [
            'relation' => 'user',
            'field' => 'role',
            'operator' => '=',
            'request_key' => 'role',
        ],

        // Range filter via closure
        'total_amount' => function ($q, $value) {
            if (isset($value['min'])) {
                $q->where('amount', '>=', $value['min']);
            }
            if (isset($value['max'])) {
                $q->where('amount', '<=', $value['max']);
            }
        }
     */
    public function filter(array $filters)
    {
        foreach ($filters as $key => $filter) {
            // Default: request field = db field
            $requestKey = is_array($filter) && isset($filter['request_key']) ? $filter['request_key'] : $key;
            $value = $this->request->input($requestKey);

            if (is_null($value) || $value === '') {
                continue;
            }

            // 1. Closure filter
            if (is_callable($filter)) {
                $filter($this->query, $value);
            }

            // 2. Relationship filter
            elseif (is_array($filter) && isset($filter['relation'], $filter['field'], $filter['operator'])) {
                $this->query->whereHas($filter['relation'], function ($q) use ($filter, $value) {
                    $q->where($filter['field'], $filter['operator'], $value);
                });
            }

            // 3. Field with operator + custom request key
            elseif (is_array($filter) && isset($filter['operator'])) {
                $field = $filter['field'] ?? $key;
                $this->query->where($field, $filter['operator'], $value);
            }

            // 4. Simple field = operator
            elseif (is_string($filter)) {
                $this->query->where($key, $filter, $value);
            }
        }

        return $this;
    }


    public function sort(array $allowed = [])
    {
        $sortField = $this->request->input('sortField');
        $sortOrder = $this->request->input('sortOrder', 1) == 1 ? 'asc' : 'desc';

        if (isset($sortField) && in_array($sortField, $allowed)) {
            $this->query->orderBy($sortField, $sortOrder);
        }

        return $this;
    }

    public function paginate()
    {
        $perPage = $this->request->input('perPage', 10);
        $page    = $this->request->input('page', 1);
        return $this->query->paginate($perPage, ['*'], 'page', $page);
    }

    public function runningNo($data)
    {
        return 1 + (($data->currentPage() - 1) * $data->perPage());
    }
}
