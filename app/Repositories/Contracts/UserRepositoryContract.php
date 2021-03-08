<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryContract extends BaseRepositoryContract
{
    public function findByEmail(string $email): ?User;

    public function findOrCreate(?int $id): User;

    public function search(?string $query): LengthAwarePaginator;


    public function updateSettings(User $user, array $settings): void;

    public function updatePassword(User $user, string $newPassword): bool;
}
