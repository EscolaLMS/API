<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryContract extends BaseRepositoryContract
{
    public function unique(array $search = [], ?int $skip = null, ?int $limit = null): Collection;
}
