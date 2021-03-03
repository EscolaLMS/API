<?php

namespace App\Services;

use App\Events\CourseCompleted;
use App\Models\Course;
use App\Models\CurriculumLecturesQuiz;
use App\Models\H5PUserProgress;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\CourseH5PProgressRepository;
use App\Services\Contracts\ProgressServiceContract;
use App\ValueObjects\CourseProgressCollection;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class ProgressService implements ProgressServiceContract
{
    private UserRepositoryContract $userRepository;

    /**
     * ProgressService constructor.
     * @param UserRepositoryContract $userRepository
     */
    public function __construct(
        UserRepositoryContract $userRepository,
        CourseH5PProgressRepository $courseH5PProgressRepository
    ) {
        $this->userRepository = $userRepository;
        $this->courseH5PProgressRepository = $courseH5PProgressRepository;
    }

    public function getByUser(User $user): Collection
    {
        $progresses = new Collection();

        foreach ($user->courses as $course) {
            $course->progress = CourseProgressCollection::make($user, $course);
            $progresses->push($course);
        }

        return $progresses;
    }

    public function update(Course $course, Authenticatable $user, array $progress): CourseProgressCollection
    {
        $courseProgressCollection = CourseProgressCollection::make($user, $course);
        $result = $courseProgressCollection->setProgress($progress);

        if ($courseProgressCollection->isFinished()) {
            $userModel = $this->userRepository->find($courseProgressCollection->getUser()->getKey());
            event(new CourseCompleted($courseProgressCollection->getCourse(), $userModel));
        }

        return $result;
    }

    public function ping(Authenticatable $user, CurriculumLecturesQuiz $lecture): void
    {
        CourseProgressCollection::make($user, $lecture->section->course)->ping($lecture);
    }

    public function h5p(Authenticatable $user, CurriculumLecturesQuiz $lecture, string $event, $json): H5PUserProgress
    {
        return $this->courseH5PProgressRepository->store($lecture, $user, $event, $json);
    }
}
