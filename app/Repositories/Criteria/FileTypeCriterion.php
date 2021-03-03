<?php


namespace App\Repositories\Criteria;

use App\Repositories\Criteria\Primitives\InCriterion;

class FileTypeCriterion extends InCriterion
{
    public function __construct(array $types, string $key = 'file_extension')
    {
        parent::__construct($key, $types);
    }
}
