<?php

namespace App\Services;

use App\Dto\CriteriaDto;
use App\Dto\UserSaveDto;
use App\Events\UserLogged;
use App\Models\CourseProgress;
use App\Models\Instructor;
use App\Models\User;
use App\Repositories\Contracts\CourseProgressRepositoryContract;
use App\Repositories\Contracts\InstructorRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Criteria\Primitives\DateCriterion;
use App\Services\Contracts\UserServiceContract;
use Carbon\Carbon;
use EscolaSoft\EscolaLms\Dtos\PaginationDto;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService implements UserServiceContract
{
    private InstructorRepositoryContract $instructorRepository;
    private UserRepositoryContract $userRepository;
    private CourseProgressRepositoryContract $courseProgressRepository;

    /**
     * UserService constructor.
     * @param InstructorRepositoryContract $instructorRepository
     * @param UserRepositoryContract $userRepository
     * @param CourseProgressRepositoryContract $courseProgressRepository
     */
    public function __construct(InstructorRepositoryContract $instructorRepository, UserRepositoryContract $userRepository, CourseProgressRepositoryContract $courseProgressRepository)
    {
        $this->instructorRepository = $instructorRepository;
        $this->userRepository = $userRepository;
        $this->courseProgressRepository = $courseProgressRepository;
    }

    public function activateInstructor(Authenticatable $user): Instructor
    {
        return $this->instructorRepository->createUsingUser($user);
    }

    public function create(UserSaveDto $userSaveDto): User
    {
        $attributes['remember_token'] = Str::random(10);
        $user = $this->userRepository->create($userSaveDto->getUserAttributes());
        $this->assignRole($user, $userSaveDto->getRoles());
        return $user;
    }

    public function update(User $user, UserSaveDto $userSaveDto): User
    {
        $attributes['remember_token'] = Str::random(10);
        $this->userRepository->update($userSaveDto->getUserAttributes(), $user->id);
        $this->assignRole($user, $userSaveDto->getRoles());
        return $user;
    }

    public function login(string $email, string $password): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (is_null($user) || !Hash::check($password, $user->password)) {
            throw new Exception('Invalid credentials');
        }

        if (!$user->hasVerifiedEmail()) {
            throw new Exception('Email not validated');
        }

        if (!$user->is_active) {
            throw new Exception("User account has been deactivated");
        }

        event(new UserLogged($user));

        return $user;
    }

    public function deleteAvatar(User $user): bool
    {
        if (!empty($user->path_avatar)) {
            $result = Storage::delete('users/' . $user->path_avatar);
            $user->update(['path_avatar' => null]);
            return $result;
        }
        return false;
    }

    public function uploadAvatar(User $user, UploadedFile $avatar): ?string
    {
        if (empty($user->path_avatar)) {
            $user->path_avatar = Str::random(40) . '.' . $avatar->clientExtension();
        }
        if ($avatar->storeAs('users', $user->path_avatar)) {
            $user->save();
            return $user->avatar_url;
        }
        return null;
    }

    private function assignRole(User $user, array $roles): void
    {
        $user->roles()->detach();
        foreach ($roles as $role_name) {
            $user->assignRole($role_name);
        }
    }

    public function search(CriteriaDto $criteriaDto, PaginationDto $paginationDto): Collection
    {
        return $this->userRepository->searchByCriteria($criteriaDto->toArray(), $paginationDto->getSkip(), $paginationDto->getLimit());
    }

    public function getUserCompletedTheMostModulesInWeek(Carbon $week): ?User
    {
        $result = [];
        $courseProgress = $this->courseProgressRepository->searchByCriteria([
            new DateCriterion('finished_at', $week->copy()->floorWeek()->format('Y-m-d'), '>='),
            new DateCriterion('finished_at', $week->copy()->endOfWeek()->format('Y-m-d'), '<='),
        ]);
        $courseProgress->each(function (CourseProgress $courseProgress) use ($week, &$result) {
            $sectionCompleted = $courseProgress->lecture->section->lectures()->whereHas('progress', function (Builder $query) use ($week) {
                $query->whereDate('finished_at', '>=', $week->copy()->floorWeek()->format('Y-m-d'));
                $query->whereDate('finished_at', '<=', $week->copy()->endOfWeek()->format('Y-m-d'));
            })->count() === $courseProgress->lecture->section->lectures->count();
            if ($sectionCompleted) {
                $result[$courseProgress->user_id] = empty($result[$courseProgress->user_id]) ? 1 : $result[$courseProgress->user_id] + 1;
            }
        });
        if ($result) {
            arsort($result);
            reset($result);
            $userId = key($result);
            return $this->userRepository->find($userId);
        }
        return null;
    }
}
