<?php

namespace App\Services\EscolaLMS\Contracts;

use Illuminate\Database\Eloquent\Model;

interface H5PContentServiceContract
{
    public function find(int $id, array $columns = ['*']): Model;

    public function delete(int $id): ?bool;
}
