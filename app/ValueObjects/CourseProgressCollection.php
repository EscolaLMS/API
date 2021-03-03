<?php

namespace App\ValueObjects;

use App\Enum\ProgressStatus;
use App\Events\CourseAssigned;
use App\Events\SectionCompleted;
use App\Models\Course;
use App\Models\CurriculumLecturesQuiz;
use App\Repositories\Contracts\CourseProgressRepositoryContract;
use App\Repositories\Contracts\CurriculumSectionRepositoryContract;
use App\ValueObjects\Contracts\CourseProgressCollectionContract;
use App\ValueObjects\Contracts\ValueObjectContract;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

class CourseProgressCollection extends ValueObject implements ValueObjectContract, CourseProgressCollectionContract
{
    private const FORGET_TRACKING_SESSION_AFTER_MINUTES = 5;

    private CourseProgressRepositoryContract $courseProgressRepository;
    private CurriculumSectionRepositoryContract $curriculumSectionRepository;
    private Session $session;

    private Authenticatable $user;
    private Course $course;
    private Collection $progress;
    private int $totalSpentTime;
    private ?Carbon $finishDate;

    public function __construct(
        CourseProgressRepositoryContract $courseProgressRepository,
        CurriculumSectionRepositoryContract $curriculumSectionRepository,
        Session $session
    ) {
        $this->courseProgressRepository = $courseProgressRepository;
        $this->curriculumSectionRepository = $curriculumSectionRepository;
        $this->session = $session;
    }

    public function build(Authenticatable $user, Course $course): self
    {
        $this->user = $user;
        $this->course = $course;
        $this->totalSpentTime = 0;
        $this->finishDate = null;
        $this->progress = $this->buildProgress();

        return $this;
    }

    public function toArray(): array
    {
        return $this->getProgress()->toArray();
    }

    /**
     * @return Authenticatable
     */
    public function getUser(): Authenticatable
    {
        return $this->user;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    public function start(): CourseProgressCollectionContract
    {
        if (!$this->isStarted()) {
            $this->user->courses()->attach($this->course->getKey());
            event(new CourseAssigned($this->user, $this->course));
        }

        return $this;
    }

    public function isStarted(): bool
    {
        return $this->user->courses()->where('course_id', $this->course->getKey())->exists();
    }

    public function isFinished(): bool
    {
        return $this->progress->whereNotIn('status', [ProgressStatus::COMPLETE])->count() == 0;
    }

    public function getProgress(): Collection
    {
        return $this->progress;
    }

    public function setProgress(array $progress): CourseProgressCollectionContract
    {
        foreach ($progress as $lectureProgress) {
            $lecture = CurriculumLecturesQuiz::findOrFail($lectureProgress['lecture_id']);
            $this->courseProgressRepository->updateInLecture(
                $lecture,
                $this->user,
                $lectureProgress['status']
            );
            if ($this->curriculumSectionRepository->isCompleted($lecture->section, $this->user)) {
                event(new SectionCompleted($lecture->section, $this->user));
            }
        }

        $this->progress = $this->buildProgress();

        return $this;
    }

    public function ping(CurriculumLecturesQuiz $lecture): self
    {
        $progress = $this->courseProgressRepository->findProgress($lecture, $this->user);
        $secondsPassed = $progress->seconds ?? 0;

        $lastTrack = $this->session->get('lecture.time.' . $lecture);
        $now = Carbon::now();

        if ($this->hasActiveProgressSession($lastTrack)) {
            $secondsDiff = $lastTrack->diffInSeconds($now);
            $secondsPassed += $secondsDiff;
            $this->courseProgressRepository->updateInLecture($lecture, $this->user, ProgressStatus::IN_PROGRESS, $secondsPassed);
        }

        $this->session->put('lecture.time.' . $lecture, $now);

        return $this;
    }

    private function buildProgress(): Collection
    {
        $progress = new Collection();

        $existingProgresses = ($this->course->progress()->where('user_id', $this->user->getKey())->get());
        $lecturesWithoutProgress = $this->course->lectures()->whereNotIn('lecture_quiz_id', $existingProgresses->pluck('lecture_id'))->where('publish', 1)->get();

        foreach ($existingProgresses as $record) {
            $progress->push([
                'lecture_id' => $record->lecture_id,
                'status' => $record->status
            ]);
            $this->totalSpentTime += $record->seconds;

            if (is_null($this->finishDate) || $this->finishDate <= $record->finished_at) {
                $this->finishDate = $record->finished_at;
            }
        }

        foreach ($lecturesWithoutProgress as $record) {
            $progress->push([
                'lecture_id' => $record->getKey(),
                'status' => ProgressStatus::INCOMPLETE
            ]);
        }

        return $progress->sortBy('lecture_id')->values();
    }

    /**
     * @param Carbon|null $lastTrack
     * @return bool
     */
    private function hasActiveProgressSession(?Carbon $lastTrack): bool
    {
        return !(is_null($lastTrack) || $lastTrack->lte(Carbon::now()->subMinutes(self::FORGET_TRACKING_SESSION_AFTER_MINUTES)));
    }

    public function getTotalSpentTime(): int
    {
        return $this->totalSpentTime;
    }

    public function getFinishDate(): ?Carbon
    {
        return $this->finishDate;
    }
}
