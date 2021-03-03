<?php

namespace App\Repositories;

use App\Enum\MediaType;
use App\Models\H5PContent;
use App\Repositories\Contracts\H5PContentRepositoryContract;
use App\Repositories\Traits\Activationable;
use EscolaSoft\LaravelH5p\Eloquents\H5pContent as EscolaH5pContent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class H5PContentRepositoryRepository
 * @package App\Repositories
 * @version December 7, 2020, 11:22 am UTC
 */
class H5PContentRepository extends BaseRepository implements H5PContentRepositoryContract
{
    use Activationable;

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
        return H5PContent::class;
    }

    /**
     * Retrieve all records with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(array $search = [], ?int $skip = null, ?int $limit = null, array $columns = ['*'], string $orderDirection = 'asc', string $orderColumn = 'id')
    {
        $query = $this->allQuery($search, $skip, $limit)->with('library');

        return $query->get($columns);
    }

    public static function usages(EscolaH5pContent $H5PContent): int
    {
        return DB::table('curriculum_sections')
            ->select('course_id')
            ->whereIn('section_id', function (Builder $query) use ($H5PContent): Builder {
                return $query
                    ->select('section_id')
                    ->from('curriculum_lectures_quiz')
                    ->where('media_type', MediaType::INTERACTIVE)
                    ->where('media', $H5PContent->getKey());
            })
            ->distinct()
            ->count();
    }
}
