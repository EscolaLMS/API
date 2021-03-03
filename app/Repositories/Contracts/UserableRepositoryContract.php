<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface UserableRepositoryContract
{
    public function allInUserContextQuery(User $user, array $search = [], ?int $skip = null, ?int $limit = null, array $columns = ['*'], string $user_key = 'user_id'): Builder;

    public function allInUserContext(User $user, array $search = [], ?int $skip = null, ?int $limit = null, array $columns = ['*'], string $user_key = 'user_id');

    public function createAsUser(User $user, array $input, string $user_key = 'user_id'): Model;

    public function updateAsUser(User $user, array $input, int $id, string $user_key = 'user_id'): Model;

    public function deleteAsUser(int $id, User $user, string $userKey = 'user_id'): ?bool;
}
