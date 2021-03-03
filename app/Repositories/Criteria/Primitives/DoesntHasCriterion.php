<?php


namespace App\Repositories\Criteria\Primitives;

use App\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class DoesntHasCriterion extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->whereDoesntHave($this->key, $this->value);
    }
}
