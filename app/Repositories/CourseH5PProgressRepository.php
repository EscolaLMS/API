<?php

namespace App\Repositories;

use App\Models\H5PUserProgress;
use App\Models\CurriculumLecturesQuiz;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class CourseProgressRepository
 * @package App\Repositories
 * @version January 7, 2021, 12:06 pm UTC
 */
class CourseH5PProgressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quiz_id',
        'user_id',
        'event',
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
        return H5PUserProgress::class;
    }

    public function store(CurriculumLecturesQuiz $lecture, Authenticatable $user, string $event, $data) : H5PUserProgress
    {
        return $this->model->updateOrCreate([
            'quiz_id' => $lecture->lecture_quiz_id,
            'user_id' => $user->id,
            'event' => $event
        ], [
            'data' => $data
            ]);
    }
}
