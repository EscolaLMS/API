<?php

namespace App\Repositories\Criteria;

use App\Repositories\Criteria\Primitives\EqualCriterion;

class FileTagCriterion extends EqualCriterion
{
    public function __construct(string $value, string $key = 'file_tag')
    {
        parent::__construct($key, $value);
    }
}
