<?php

namespace App\Services;

use App\Dto\MetricsDto;
use EscolaLms\Core\Enums\UserRole;
use App\Models\Course;
use App\Models\User;
use EscolaLms\Core\Repositories\Criteria\Criterion;
use EscolaLms\Core\Repositories\Criteria\PeriodCriterion;
use EscolaLms\Core\Repositories\Criteria\RoleCriterion;
use App\Services\Contracts\StatsServiceContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StatsService implements StatsServiceContract
{
    public function adminMetrics(): MetricsDto
    {
        $studentCriterion = [new RoleCriterion(UserRole::STUDENT)];
        $instructorCriterion = [new RoleCriterion(UserRole::INSTRUCTOR)];

        $now = Carbon::now();
        $weekAgo = Carbon::now()->subWeek();

        return new MetricsDto(
            $this->count(new Course),
            $this->count(new User, $studentCriterion),
            $this->count(new User, $instructorCriterion),
            $this->countByPeriod(new Course, $weekAgo),
            $this->countByPeriod(new User, $weekAgo, $now, $studentCriterion),
            $this->countByPeriod(new User, $weekAgo, $now, $instructorCriterion)
        );
    }

    public function countByPeriod(Model $model, Carbon $from, ?Carbon $to = null, array $criteria = []): int
    {
        if (is_null($to)) {
            $to = Carbon::now();
        }

        $criteria = [
            new PeriodCriterion($from, $to),
            ...$criteria
        ];

        return $this->count($model, $criteria);
    }

    public function count(Model $model, array $criteria = []): int
    {
        $query = $model->newQuery();
        $query = $this->applyCriteria($query, $criteria);
        return $query->count();
    }

    private function applyCriteria(Builder $query, array $criteria)
    {
        foreach ($criteria as $criterion) {
            if ($criterion instanceof Criterion) {
                $query = $criterion->apply($query);
            }
        }

        return $query;
    }
}
