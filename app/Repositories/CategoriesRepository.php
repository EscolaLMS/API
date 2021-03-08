<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoriesRepositoryContract;
use App\Repositories\Traits\Activationable;
use Carbon\Carbon;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CategoriesRepositoryRepository
 * @package App\Repositories
 * @version December 7, 2020, 11:22 am UTC
 */
class CategoriesRepository extends BaseRepository implements CategoriesRepositoryContract
{
    use Activationable;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'slug',
        'icon_class',
        'is_active',
        'parent_id'
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
        return Category::class;
    }

    public function allRoots(array $search = [], ?int $skip = null, ?int $limit = null): Collection
    {
        return $this->allQuery($search, $skip, $limit)->whereNull('parent_id')->get();
    }

    public function getByPopularity(PaginationDto $pagination, ?Carbon $from = null, ?Carbon $to = null): \Illuminate\Support\Collection
    {
        $query = $this->model->newQuery()
            ->withCount(['users' => function ($q) use ($from, $to) {
                if (!is_null($from)) {
                    $q->where('category_user.created_at', '>=', $from);
                }

                if (!is_null($to)) {
                    $q->where('category_user.created_at', '<=', $to);
                }
            }]);

        if (!is_null($from)) {
            $query->where('categories.created_at', '>=', $from);
        }

        if (!is_null($to)) {
            $query->where('categories.created_at', '<=', $to);
        }

        $query->orderBy('users_count', 'DESC');

        $this->applyPaginationDto($query, $pagination);

        return $query->get();
    }
}
