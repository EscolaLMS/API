<?php

namespace App\Services;

use App\Events\CourseCompleted;
use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\CurriculumLecturesQuiz;
use App\Models\H5PUserProgress;
use App\Models\User;
use App\Repositories\Contracts\CourseProgressRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\CourseH5PProgressRepository;
use App\Services\Contracts\ProgressServiceContract;
use App\ValueObjects\CourseProgressCollection;
use Carbon\Carbon;
use EscolaLms\Core\Repositories\Criteria\Primitives\DateCriterion;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProgressService implements ProgressServiceContract
{
    private UserRepositoryContract $userRepository;
    private CourseProgressRepositoryContract $courseProgressRepository;
    private CourseH5PProgressRepository $courseH5PProgressRepository;

    /**
     * ProgressService constructor.
     * @param UserRepositoryContract $userRepository
     * @param CourseProgressRepositoryContract $courseProgressRepository
     * @param CourseH5PProgressRepository $courseH5PProgressRepository
     */
    public function __construct(UserRepositoryContract $userRepository, CourseProgressRepositoryContract $courseProgressRepository, CourseH5PProgressRepository $courseH5PProgressRepository)
    {
        $this->userRepository = $userRepository;
        $this->courseProgressRepository = $courseProgressRepository;
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

    public function getUserCompletedTheMostModulesInWeek(Carbon $week): ?User
    {
        $result = [];
        $courseProgress = $this->courseProgressRepository->searchByCriteria([
            new DateCriterion('finished_at', $week->copy()->floorWeek()->format('Y-m-d'), '>='),
            new DateCriterion('finished_at', $week->copy()->endOfWeek()->format('Y-m-d'), '<='),
        ]);
        $courseProgress->each(function (CourseProgress $courseProgress) use ($week, &$result) {
            $sectionCompleted = $courseProgress->lecture->section->lectures()->whereHas('progress', function (Builder $query) use ($week) {
                    $query->whereDate('finished_at', '>=', $week->copy()->floorWeek()->format('Y-m-d'));
                    $query->whereDate('finished_at', '<=', $week->copy()->endOfWeek()->format('Y-m-d'));
                })->count() === $courseProgress->lecture->section->lectures->count();
            if ($sectionCompleted) {
                $result[$courseProgress->user_id] = empty($result[$courseProgress->user_id]) ? 1 : $result[$courseProgress->user_id] + 1;
            }
        });
        if ($result) {
            arsort($result);
            reset($result);
            $userId = key($result);
            return $this->userRepository->find($userId);
        }
        return null;
    }
}
