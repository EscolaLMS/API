<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

class NotRelatedCourses extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query
            ->where('courses.id', '!=', $this->value->getKey())
            ->whereNotIn('courses.id', $this->value->related()->pluck('id'));
    }
}
