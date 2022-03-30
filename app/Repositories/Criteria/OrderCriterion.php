<?php

namespace App\Repositories\Criteria;

use EscolaLms\Core\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class OrderCriterion extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->orderBy($this->key, $this->value);
    }
}
