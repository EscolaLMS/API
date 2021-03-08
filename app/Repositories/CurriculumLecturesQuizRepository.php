<?php

namespace App\Repositories;

use App\Models\CurriculumLecturesQuiz;
use App\Repositories\Contracts\CurriculumLecturesQuizRepositoryContract;
use EscolaLms\Core\Repositories\BaseRepository;

/**
 * Class CurriculumLecturesQuizRepository
 * @package App\Repositories
 * @version December 9, 2020, 1:17 pm UTC
 */
class CurriculumLecturesQuizRepository extends BaseRepository implements CurriculumLecturesQuizRepositoryContract
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'section_id',
        'type',
        'title',
        'description',
        'contenttext',
        'media',
        'media_type',
        'sort_order',
        'publish',
        'resources'
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
        return CurriculumLecturesQuiz::class;
    }

    public function sort(array $data): void
    {
        foreach ($data as $row) {
            $this->model->newQuery()->where($this->model->getKeyName(), $row['id'])->update([
                'sort_order' => $row['position'],
                'section_id' => $row['sectionid']
            ]);
        }
    }

    public function setStatus(CurriculumLecturesQuiz $curriculumLecturesQuiz, bool $published): void
    {
        $curriculumLecturesQuiz->update(['publish' => $published]);
    }
}
