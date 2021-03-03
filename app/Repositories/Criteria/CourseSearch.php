<?php


namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CourseSearch extends Criterion
{
    public function __construct($value = null)
    {
        parent::__construct(null, $value);
    }

    public function apply(Builder $query): Builder
    {
        return $query->where(function (Builder $query): Builder {
            $like = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'pgsql' ? 'ILIKE' : 'LIKE';


            return $query
                ->where('courses.course_title', $like, '%' . $this->value . '%')
                ->orWhere('courses.course_slug', $like, '%' . $this->value . '%')
                ->orWhere('categories.name', $like, '%' . $this->value . '%');
        });
    }
}
