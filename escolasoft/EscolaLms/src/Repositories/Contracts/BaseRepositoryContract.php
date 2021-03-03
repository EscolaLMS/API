<?php

namespace EscolaSoft\EscolaLms\Repositories\Contracts;

use EscolaSoft\EscolaLms\Dtos\PaginationDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseRepositoryContract
{
    public function makeModel(): Model;

    public function paginate(int $perPage, array $columns = ['*']): LengthAwarePaginator;

    public function allQuery(array $search = [], ?int $skip = null, ?int $limit = null): Builder;

    public function all(array $search = [], ?int $skip = null, ?int $limit = null, array $columns = ['*'], string $orderDirection = 'asc', string $orderColumn = 'id');

    public function allPaginated(array $search = [], PaginationDto $pagination, array $columns = ['*']);

    public function allWithOrder(array $search = [], ?int $skip = null, ?int $limit = null, ?string $orderBy = null, string $direction = 'asc', array $columns = ['*']);

    public function create(array $input): Model;

    public function allIn(string $column, Collection $list): Collection;

    public function createUsingModel(Model $model): Model;

    public function searchByCriteria(array $criteria, ?int $skip = null, ?int $limit = null): Collection;

    public function applyCriteria(Builder $query, array $criteria): Builder;

    public function queryWithAppliedCriteria(array $criteria): Builder;

    public function getFieldsSearchable();

    public function remove(Model $model): void;

    public function getEmptyColumns(): object;

    public function find(int $id, array $columns = ['*']): ?Model;

    public function update(array $input, int $id): Model;

    public function delete(int $id): ?bool;

    public function deleteWhere(array $payload): ?bool;

    public function count(): int;

    public function countByCriteria(array $criteria): int;

    public function likeParam(string $query): array;
}
