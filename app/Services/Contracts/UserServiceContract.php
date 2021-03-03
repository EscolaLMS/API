<?php

namespace App\Services\Contracts;

use App\Dto\CriteriaDto;
use App\Dto\UserSaveDto;
use App\Models\Instructor;
use App\Models\User;
use Carbon\Carbon;
use EscolaSoft\EscolaLms\Dtos\PaginationDto;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface UserServiceContract
{
    public function activateInstructor(Authenticatable $user): Instructor;

    public function create(UserSaveDto $userSaveDto): ?User;

    public function update(User $user, UserSaveDto $userSaveDto): ?User;

    public function uploadAvatar(User $user, UploadedFile $avatar): ?string;

    public function deleteAvatar(User $user): bool;

    public function login(string $email, string $password): User;

    public function search(CriteriaDto $criteriaDto, PaginationDto $paginationDto): Collection;

    public function getUserCompletedTheMostModulesInWeek(Carbon $week): ?User;
}
