<?php


namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

class CourseSearch extends Criterion
{
    public function __construct($value = null)
    {
        parent::__construct(null, $value);
    }

    public function apply(Builder $query): Builder
    {
        return $query->where(function (Builder $query): Builder {
            return $query
                ->where('courses.course_title', 'ILIKE', '%' . $this->value . '%')
                ->orWhere('courses.course_slug', 'ILIKE', '%' . $this->value . '%')
                ->orWhere('categories.name', 'ILIKE', '%' . $this->value . '%');
        });
    }
}
