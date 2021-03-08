<?php


namespace App\Repositories\Contracts;

use App\Models\Course;
use App\Models\Instructor;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Builder;

interface CategoriesRepositoryContract extends BaseRepositoryContract, ActivationContract
{
    public function allRoots(array $search = [], ?int $skip = null, ?int $limit = null);
}
