<?php

namespace App\Repositories\Contracts;

use App\Models\CurriculumLecturesQuiz;

interface CurriculumLecturesQuizRepositoryContract extends BaseRepositoryContract, SortableContract
{
    public function setStatus(CurriculumLecturesQuiz $curriculumLecturesQuiz, bool $published): void;
}
