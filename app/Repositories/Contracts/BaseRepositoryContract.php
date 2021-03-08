<?php

namespace App\Repositories\Contracts;

use EscolaLms\Core\Dtos\Contracts\CompareDtoContract;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract as BaseEscolaLmsRepositoryContract;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryContract extends BaseEscolaLmsRepositoryContract, UserableRepositoryContract
{
    public function patch(CompareDtoContract $dto): Model;
}
