<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use EscolaSoft\EscolaLms\Dtos\PaginationDto;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface CourseRepositoryContract extends BaseRepositoryContract
{
    public function findBySlug(string $slug): Course;

    public function queryAll(): Builder;

    public function getStudentCourses(User $user): Collection;

    public function inCategories(Collection $categories, ?int $skip = null, ?int $limit = null): Collection;

    public function inCategoriesQuery(Collection $categories, ?int $skip = null, ?int $limit = null): Builder;

    public function relatedMany(array $courses): Collection;

    public function popular(?int $skip = null, ?int $limit = null): Collection;

    public function userOwnsCourse(Course $course, User $user): bool;

    public function getByPopularity(PaginationDto $pagination, ?Carbon $from = null, ?Carbon $to = null): Collection;

    public function isSuitCompleted(Course $course, User $user): bool;

    public function countOfCompletedSuit(User $user): int;
}
