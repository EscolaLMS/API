<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TagRepository
 * @package App\Repositories
 * @version December 7, 2020, 1:53 pm UTC
*/

class TagRepository extends BaseRepository implements TagRepositoryContract
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'morphable_type',
        'morphable_id'
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
        return Tag::class;
    }

    /**
     * Build a query for retrieving unique titles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function unique(array $search = [], ?int $skip = null, ?int $limit = null): Collection
    {
        return $this->model->select('title')->distinct('title')->get();
    }
}
