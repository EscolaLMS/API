<?php


namespace App\Repositories\Criteria;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class PeriodCriterion extends Criterion
{
    public function __construct(Carbon $from, Carbon $to, string $dateField = 'created_at')
    {
        parent::__construct($dateField, ['from' => $from->startOfDay(), 'to' => $to->endOfDay()]);
    }

    public function apply(Builder $query): Builder
    {
        return $query
            ->whereDate($this->key, '>=', $this->value['from'])
            ->whereDate($this->key, '<=', $this->value['to']);
    }
}
