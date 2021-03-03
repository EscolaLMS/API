<?php


namespace App\Repositories\Criteria;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class UserCriterion extends Criterion
{
    public function __construct(string $key, Authenticatable $user)
    {
        parent::__construct($key, $user);
    }

    public function apply(Builder $query): Builder
    {
        return $query->where($this->key, $this->value->getKey());
    }
}
