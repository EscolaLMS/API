<?php

namespace EscolaSoft\EscolaLms\Repositories;

use App\Models\User;
use App\Repositories\Criteria\Criterion;
use EscolaSoft\EscolaLms\Dtos\Contracts\CompareDtoContract;
use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use EscolaSoft\EscolaLms\Dtos\PaginationDto;
use EscolaSoft\EscolaLms\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements BaseRepositoryContract
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     *
     * @throws \Exception
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Get searchable fields array
     *
     * @return array
     */
    abstract public function getFieldsSearchable();

    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();

    /**
     * Make Model instance
     *
     * @return Model
     * @throws \Exception
     *
     */
    public function makeModel(): Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage, array $columns = ['*']): LengthAwarePaginator
    {
        $query = $this->allQuery();

        return $query->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery(array $search = [], ?int $skip = null, ?int $limit = null): Builder
    {
        $query = $this->model->newQuery();

        if (count($search)) {
            foreach ($search as $key => $value) {
                if (in_array($key, $this->getFieldsSearchable())) {
                    if (is_array($value)) {
                        $query->where($key, $value[0], $value[1]);
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        }

        return $this->applyPagination($query, $skip, $limit);
    }

    /**
     * @param Builder|Relation $query
     *
     * @return Builder|Relation
     */
    public function applyPagination($query, ?int $skip = null, ?int $limit = null)
    {
        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }

    public function applyPaginationDto($query, PaginationDto $dto)
    {
        if (!is_null($dto->getSkip())) {
            $query->skip($dto->getSkip());
        }

        if (!is_null($dto->getLimit())) {
            $query->limit($dto->getLimit());
        }

        return $query;
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
        $query = $this->allQuery($search, $skip, $limit);

        return $query->orderBy($orderColumn, $orderDirection)->get($columns);
    }

    public function allPaginated(array $search = [], PaginationDto $pagination, array $columns = ['*'])
    {
        return $this->all($search, $pagination->getSkip(), $pagination->getLimit(), $columns);
    }

    public function allWithOrder(array $search = [], ?int $skip = null, ?int $limit = null, ?string $orderBy = null, string $direction = 'asc', array $columns = ['*'])
    {
        $query = $this->allQuery($search, $skip, $limit)->orderBy($orderBy, $direction);

        return $query->get($columns);
    }

    /**
     * Retrieve all records in user context with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allInUserContextQuery(User $user, array $search = [], ?int $skip = null, ?int $limit = null, array $columns = ['*'], string $user_key = 'user_id'): Builder
    {
        return $this->allQuery($search, $skip, $limit)->where($user_key, $user->getKey());
    }

    /**
     * Retrieve all records in user context with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allInUserContext(User $user, array $search = [], ?int $skip = null, ?int $limit = null, array $columns = ['*'], string $user_key = 'user_id')
    {
        $query = $this->allInUserContextQuery($user, $search, $skip, $limit, $columns, $user_key);

        return $query->get($columns);
    }

    /**
     * @param string $column
     * @param Collection $list
     * @return Collection
     */
    public function allIn(string $column, Collection $list): Collection
    {
        return $this->model->newQuery()->whereIn($column, $list)->get();
    }

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create(array $input): Model
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    public function createAsUser(User $user, array $input, string $user_key = 'user_id'): Model
    {
        $input[$user_key] = $user->getKey();
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    public function createUsingModel(Model $model): Model
    {
        $model->save();
        return $model;
    }

    /**
     * @param array $criteria
     * @param int|null $skip
     * @param int|null $limit
     *
     * @return Collection
     */
    public function searchByCriteria(array $criteria, ?int $skip = null, ?int $limit = null): Collection
    {
        $query = $this->model->newQuery();
        $query = $this->applyCriteria($query, $criteria);
        $query = $this->applyPagination($query, $skip, $limit);

        return $query->get();
    }

    public function queryWithAppliedCriteria(array $criteria): Builder
    {
        $query = $this->model->newQuery();

        return $this->applyCriteria($query, $criteria);
    }

    public function applyCriteria(Builder $query, array $criteria): Builder
    {
        foreach ($criteria as $criterion) {
            if ($criterion instanceof Criterion) {
                $query = $criterion->apply($query);
            }
        }

        return $query;
    }

    public function remove(Model $model): void
    {
        $model->delete();
    }

    public function getEmptyColumns(): object
    {
        $columns = array();
        $prefix = \DB::getTablePrefix();
        foreach (\DB::getSchemaBuilder()->getColumnListing($prefix . $this->model->getTable()) as $column) {
            $columns[$column] = '';
        }

        return (object)$columns;
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find(int $id, array $columns = ['*']): ?Model
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update(array $input, int $id): Model
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    public function updateUsingDto(DtoContract $dto, Model $model, bool $ignoreEmpty = false): Model
    {
        $payload = [];

        foreach ($dto->toArray() as $key => $value) {
            if (!empty($value) || $ignoreEmpty) {
                $payload[$key] = $value;
            }
        }

        return $this->update($payload, $model->getKey());
    }

    public function updateAsUser(User $user, array $input, int $id, string $user_key = 'user_id'): Model
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $input[$user_key] = $user->getKey();
        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * @param int $id
     *
     * @return bool|mixed|null
     * @throws \Exception
     *
     */
    public function delete(int $id): ?bool
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }

    public function deleteAsUser(int $id, User $user, string $userKey = 'user_id'): ?bool
    {
        $query = $this->model->newQuery();

        $model = $query->where($userKey, $user->getKey())->findOrFail($id);

        return $model->delete();
    }

    public function patch(CompareDtoContract $dto): Model
    {
        return $this->model->updateOrCreate($dto->identifier(), $dto->toArray());
    }

    public function deleteWhere(array $payload): ?bool
    {
        return $this->model->newQuery()->where($payload)->delete();
    }

    public function count(): int
    {
        $this->model->newQuery()->count();
    }

    public function countByCriteria(array $criteria): int
    {
        $query = $this->model->newQuery();
        $query = $this->applyCriteria($query, $criteria);
        return $query->count();
    }

    public function likeParam(string $query): array
    {
        $dbDriver = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        return $dbDriver === 'pgsql' ? ['ILIKE', '%' . $query . '%'] : ['LIKE', '%' . $query . '%'];
    }
}
