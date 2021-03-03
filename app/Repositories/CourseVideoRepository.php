<?php

namespace App\Repositories;

use App\Models\CourseVideos;
use App\Models\User;
use App\Repositories\Contracts\CourseVideoRepositoryContract;
use Illuminate\Support\Collection;

/**
 * Class CourseRepository
 * @package App\Repositories
 * @version December 1, 2020, 11:46 am UTC
 */
class CourseVideoRepository extends BaseRepository implements CourseVideoRepositoryContract
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'video_title',
        'video_name',
        'video_type',
        'duration',
        'image_name',
        'video_tag',
        'uploader_id',
        'course_id',
        'processed'
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
        return CourseVideos::class;
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function getByUser(User $user): Collection
    {
        return $this->model->newQuery()->where('uploader_id', $user->getKey())->get();
    }
}
