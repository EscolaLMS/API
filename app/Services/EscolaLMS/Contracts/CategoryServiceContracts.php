<?php

namespace App\Services\EscolaLMS\Contracts;

use App\Dto\CategoryCreateDto;

interface CategoryServiceContracts
{
    public function getList(?string $search = null);

    public function find(?string $id = null);

    public function save(CategoryCreateDto $blogDto): string;

    public function delete(string $id): void;
}
