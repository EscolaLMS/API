<?php


namespace App\Repositories\Criteria\Primitives;

use App\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class ModelCriterion extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->where($this->key, $this->value->getKey());
    }
}
