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
class CourseInteractiveElementRepository extends BaseRepository implements CourseVideoRepositoryContract
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'created_at',
        'updated_at',
        'user_id',
        'title',
        'library_id',
        'parameters',
        'filtered',
        'slug',
        'embed_type',
        'disable',
        'content_type',
        'author',
        'license',
        'keywords',
        'description',
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
