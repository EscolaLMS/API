<?php

namespace App\Repositories\Contracts;

use App\Dto\CurriculumSectionDto;
use App\Models\CurriculumSection;
use App\Models\User;

interface CurriculumSectionRepositoryContract extends BaseRepositoryContract, SortableContract
{
    public function createUsingDto(CurriculumSectionDto $curriculumSectionDto): CurriculumSection;

    public function updateSection(CurriculumSectionDto $curriculumSectionDto): CurriculumSection;

    public function isCompleted(CurriculumSection $curriculumSection, User $user): bool;
}
