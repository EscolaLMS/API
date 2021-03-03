<?php


namespace App\Repositories\Criteria\Primitives;

use App\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class InCriterion extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->whereIn($this->key, $this->value);
    }
}
