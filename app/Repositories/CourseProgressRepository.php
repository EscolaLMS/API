<?php

namespace App\Repositories;

use App\Enum\ProgressStatus;
use App\Models\CourseProgress;
use App\Models\CurriculumLecturesQuiz;
use App\Repositories\Contracts\CourseProgressRepositoryContract;
use Carbon\Carbon;
use EscolaLms\Core\Repositories\BaseRepository;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class CourseProgressRepository
 * @package App\Repositories
 * @version January 7, 2021, 12:06 pm UTC
 */
class CourseProgressRepository extends BaseRepository implements CourseProgressRepositoryContract
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'course_id',
        'user_id',
        'status',
        'finished_at'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CourseProgress::class;
    }

    public function updateInLecture(CurriculumLecturesQuiz $lecture, Authenticatable $user, int $status, ?int $seconds = null): void
    {
        $update = ['status' => $status];
        if (!is_null($seconds)) {
            $update['seconds'] = $seconds;
        }

        if ($status == ProgressStatus::COMPLETE) {
            $update['finished_at'] = Carbon::now();
        }

        $lecture->progress()->updateOrCreate([
            'user_id' => $user->getKey(),
            'course_id' => $lecture->section->course->getKey()
        ], $update);
    }

    public function findProgress(CurriculumLecturesQuiz $lecture, Authenticatable $user): ?CourseProgress
    {
        return $this->model->where('lecture_id', $lecture->getKey())->where('user_id', $user->getKey())->first();
    }
}
