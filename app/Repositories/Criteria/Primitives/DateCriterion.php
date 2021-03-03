<?php


namespace App\Repositories\Criteria\Primitives;

use App\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class DateCriterion extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->whereDate($this->key, $this->operator, $this->value);
    }
}
