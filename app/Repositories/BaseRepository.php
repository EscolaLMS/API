<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\BaseRepositoryContract;
use EscolaLms\Core\Dtos\Contracts\CompareDtoContract;
use EscolaLms\Core\Repositories\BaseRepository as BaseEscolaRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository extends BaseEscolaRepository implements BaseRepositoryContract
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

    public function createAsUser(User $user, array $input, string $user_key = 'user_id'): Model
    {
        $input[$user_key] = $user->getKey();
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
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
}
