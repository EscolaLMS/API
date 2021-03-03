<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface ActivationContract extends BaseRepositoryContract
{
    public function getActive(): Collection;
}
