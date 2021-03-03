<?php

namespace App\Repositories\Contracts;

use EscolaSoft\EscolaLms\Dtos\Contracts\CompareDtoContract;
use EscolaSoft\EscolaLms\Repositories\Contracts\BaseRepositoryContract as BaseEscolaLmsRepositoryContract;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryContract extends BaseEscolaLmsRepositoryContract, UserableRepositoryContract
{
    public function patch(CompareDtoContract $dto): Model;
}
