<?php

namespace App\Repositories\Contracts;

use App\Models\CurriculumLecturesQuiz;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;

interface CurriculumLecturesQuizRepositoryContract extends BaseRepositoryContract, SortableContract
{
    public function setStatus(CurriculumLecturesQuiz $curriculumLecturesQuiz, bool $published): void;
}
