<?php

namespace App\Repositories;

use App\Dto\CurriculumSectionDto;
use App\Models\CurriculumLecturesQuiz;
use App\Models\CurriculumSection;
use App\Models\User;
use App\Repositories\Contracts\CurriculumSectionRepositoryContract;
use EscolaLms\Core\Repositories\BaseRepository;

/**
 * Class CurriculumSectionRepository
 * @package App\Repositories
 * @version December 9, 2020, 11:40 am UTC
 */
class CurriculumSectionRepository extends BaseRepository implements CurriculumSectionRepositoryContract
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'course_id',
        'title',
        'sort_order'
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
        return CurriculumSection::class;
    }

    public function createUsingDto(CurriculumSectionDto $curriculumSectionDto): CurriculumSection
    {
        return $curriculumSectionDto->getCourse()->sections()->create($curriculumSectionDto->toArray());
    }

    public function updateSection(CurriculumSectionDto $curriculumSectionDto): CurriculumSection
    {
        $curriculumSectionDto->getSection()->update($curriculumSectionDto->toArray());
        return $curriculumSectionDto->getSection();
    }

    public function sort(array $data): void
    {
        foreach ($data as $row) {
            $this->model->newQuery()->where($this->model->getKeyName(), $row['id'])->update([
                'sort_order' => $row['position']
            ]);
        }
    }

    public function isCompleted(CurriculumSection $curriculumSection, User $user): bool
    {
        return !$curriculumSection->lectures()->get()->filter(function (CurriculumLecturesQuiz $lecture) use ($user) {
            return $lecture->progress()->where([
                'user_id' => $user->getKey(),
                'course_id' => $lecture->section->course->getKey(),
            ])->whereNull('finished_at')->exists();
        })->count();
    }
}
