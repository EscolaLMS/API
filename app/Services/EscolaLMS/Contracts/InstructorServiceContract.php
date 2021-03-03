<?php

namespace App\Services\EscolaLMS\Contracts;

use App\Dto\InstructorCreateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InstructorServiceContract
{
    public function getInstructorList(): LengthAwarePaginator;
    public function create(InstructorCreateDto $instructorCreateDto): bool;
}
