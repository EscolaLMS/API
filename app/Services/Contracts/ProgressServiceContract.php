<?php

namespace App\Services\Contracts;

use App\Models\Course;
use App\Models\CurriculumLecturesQuiz;
use App\Models\User;
use App\ValueObjects\CourseProgressCollection;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

interface ProgressServiceContract
{
    public function getByUser(User $user): Collection;

    public function update(Course $course, Authenticatable $user, array $progress): CourseProgressCollection;

    public function ping(Authenticatable $user, CurriculumLecturesQuiz $lecture): void;
}
