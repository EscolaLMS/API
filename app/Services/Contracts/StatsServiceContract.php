<?php

namespace App\Services\Contracts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

interface StatsServiceContract
{
    public function countByPeriod(Model $model, Carbon $from, ?Carbon $to = null, array $criteria = []): int;

    public function count(Model $model): int;
}
