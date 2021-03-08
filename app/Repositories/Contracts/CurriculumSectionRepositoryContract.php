<?php

namespace App\Repositories\Contracts;

use App\Dto\CurriculumSectionDto;
use App\Models\CurriculumSection;
use App\Models\User;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;

interface CurriculumSectionRepositoryContract extends BaseRepositoryContract, SortableContract
{
    public function createUsingDto(CurriculumSectionDto $curriculumSectionDto): CurriculumSection;

    public function updateSection(CurriculumSectionDto $curriculumSectionDto): CurriculumSection;

    public function isCompleted(CurriculumSection $curriculumSection, User $user): bool;
}
