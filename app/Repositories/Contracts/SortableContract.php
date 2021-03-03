<?php

namespace App\Repositories\Contracts;

interface SortableContract
{
    public function sort(array $lectures): void;
}
