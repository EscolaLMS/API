<?php

namespace Tests;

use App\Enum\ProgressStatus;
use App\Models\Course;
use App\Models\User;
use App\ValueObjects\CourseProgressCollection;

trait ProgressConfigurable
{
    /**
     * @param Course $course
     * @param int $status
     * @return array
     */
    private function getProgressUpdate(Course $course, int $status = ProgressStatus::COMPLETE): array
    {
        $progress = [];
        foreach ($course->lectures->pluck('lecture_quiz_id') as $lectureId) {
            $progress[] = [
                'lecture_id' => $lectureId,
                'status' => $status
            ];
        }
        return $progress;
    }

    private function progressUpdate(Course $course, User $user, int $status = ProgressStatus::COMPLETE): CourseProgressCollection
    {
        return CourseProgressCollection::make($user, $course)
            ->start()
            ->setProgress($this->getProgressUpdate($course, $status));
    }
}
