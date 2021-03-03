<?php

namespace App\Services\EscolaLMS\Contracts;

use App\Dto\BlogCreateDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface BlogServiceContract
{
    public function getList(?string $search = null): LengthAwarePaginator;

    public function find(?string $id = null);

    public function save(BlogCreateDto $blogDto): string;

    public function delete(string $id): void;
}
