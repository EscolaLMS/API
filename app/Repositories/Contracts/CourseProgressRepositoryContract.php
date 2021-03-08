<?php

namespace App\Repositories\Contracts;

use App\Models\CourseProgress;
use App\Models\CurriculumLecturesQuiz;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;

interface CourseProgressRepositoryContract extends BaseRepositoryContract
{
    public function updateInLecture(CurriculumLecturesQuiz $lecture, Authenticatable $user, int $status, ?int $seconds = null): void;

    public function findProgress(CurriculumLecturesQuiz $lecture, Authenticatable $user): ?CourseProgress;
}
