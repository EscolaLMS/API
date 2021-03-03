<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

abstract class Criterion
{
    protected ?string $key;
    protected $value;
    protected $operator;

    public function __construct(?string $key = null, $value = null, $operator = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->operator = $operator;
    }

    abstract public function apply(Builder $query): Builder;
}
