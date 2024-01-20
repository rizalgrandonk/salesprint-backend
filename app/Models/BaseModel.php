<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
    /**
     * Scope a query to only include records with a certain status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array<string mixed> param
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeGetDataTable($query, $param) {

        if (isset($param['with'])) {
            $with = $param['with'];
            $query->with($with);
        }

        if (isset($param['withCount'])) {
            $withCount = $param['withCount'];
            $query->withCount($withCount);
        }

        if (isset($param['orderBy'])) {
            $orderBy = $param['orderBy'];
            $field = array_keys($orderBy)[0];
            $order = $orderBy[$field];

            if ($field && $field !== '' && $order && $order !== '') {
                $query->orderBy($field, $order);
            }
        }

        if (isset($param['filters'])) {
            $filters = $param['filters'];
            foreach ($filters as $key => $value) {
                if ($key && $key !== '' && $value && $value !== '') {
                    $query->where($key, $value);
                }
            }
        }

        if (isset($param['search'])) {
            $search = $param['search'];
            $field = array_keys($search)[0];
            $value = $search[$field];

            if ($field && $field !== '' && $value && $value !== '') {
                $query->where($field, 'LIKE', '%' . $value . '%');
            }
        }

        $limit = isset($param['limit']) ? (int) $param['limit'] : 10;
        return $query->paginate($limit);
    }

    /**
     * Scope a query to only include records with a certain status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array<string mixed> param
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParamsWith($query, $param) {
        if (isset($param['with'])) {
            $with = $param['with'];
            $query->with($with);
        }

        if (isset($param['withCount'])) {
            $withCount = $param['withCount'];
            $query->withCount($withCount);
        }
        return $query;
    }
}
