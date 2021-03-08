<?php

namespace App\Repositories\Contracts;

use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Support\Collection;

interface ActivationContract extends BaseRepositoryContract
{
    public function getActive(): Collection;
}
