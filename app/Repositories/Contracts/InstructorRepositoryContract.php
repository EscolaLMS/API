<?php

namespace App\Repositories\Contracts;

use App\Models\Instructor;
use App\Models\User;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface InstructorRepositoryContract extends BaseRepositoryContract
{
    public function createUsingUser(User $user): Instructor;

    public function findBySlug(string $slug): Instructor;

    public function queryAll(): Builder;

    public function getMetrics(int $instructor_id): array;

    public function getInstructorCourses(int $instructorId): LengthAwarePaginator;

    public function getInstructorCredits(int $instructorId): LengthAwarePaginator;

    public function getInstructorWithdrawRequests(int $instructorId): LengthAwarePaginator;

    public function adminMetrics(): array;
}
