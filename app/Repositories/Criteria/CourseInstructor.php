<?php


namespace App\Repositories\Criteria;

use App\Models\Instructor;
use Illuminate\Database\Eloquent\Builder;

class CourseInstructor extends Criterion
{
    public function __construct(Instructor $value)
    {
        parent::__construct(null, $value);
    }

    public function apply(Builder $query): Builder
    {
        return $query->where('courses.instructor_id', $this->value->getKey());
    }
}
