<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Course;
use App\Models\CurriculumSection;
use App\Models\User;
use App\Repositories\Contracts\CourseRepositoryContract;
use App\ValueObjects\CourseProgressCollection;
use Carbon\Carbon;
use EscolaLms\Core\Dtos\PaginationDto;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class CourseRepository
 * @package App\Repositories
 * @version December 1, 2020, 11:46 am UTC
 */
class CourseRepository extends BaseRepository implements CourseRepositoryContract
{

    /**
     * Recursive flatten object by given $key
     * @param object $input an object with children key
     * @param string $key children key
     */
    public static function flatten($input, $key)
    {
        $output = [];
        foreach ($input as $object) {
            $children = isset($object->$key) ? $object->$key : [];
            $object->$key = [];
            $output[] = $object;
            $children = self::flatten($children, $key);
            foreach ($children as $child) {
                $output[] = $child;
            }
        }
        return $output;
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'instructor_id',
        'category_id',
        'instruction_level_id',
        'course_title',
        'course_slug',
        'keywords',
        'overview',
        'course_image',
        'thumb_image',
        'course_video',
        'duration',
        'price',
        'strike_out_price',
        'is_active',
        'summary',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Course::class;
    }

    public function findBySlug(string $slug): Course
    {
        return $this->model->newQuery()->where('course_slug', $slug)->firstOrFail();
    }

    public function queryAll(): Builder
    {
        return $this->model->newQuery()
            ->select('courses.*', 'categories.name as category_name')
            ->leftJoin('categories', 'categories.id', '=', 'courses.category_id');
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update(array $input, int $id): Model
    {
        $tags = $input['tags'];
        unset($input['tags']);
        $model = parent::update($input, $id);

        $model->tags()->delete();
        $model->tags()->createMany($tags);

        return $model;
    }

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create(array $input): Model
    {
        $tags = $input['tags'];
        unset($input['tags']);
        $model = parent::create($input);
        $model->tags()->createMany($tags);

        return $model;
    }

    /**
     * Retrieve all records with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(array $search = [], ?int $skip = null, ?int $limit = null, array $columns = ['*'], string $orderDirection = 'asc', string $orderColumn = 'id')
    {
        $like = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'pgsql' ? 'ILIKE' : 'LIKE';

        if (isset($search) && isset($search['course_title'])) {
            $search['course_title'] = [$like, "%" . $search['course_title'] . "%"];
        }

        /** search main category and all subcategories */
        if (isset($search) && isset($search['category_id'])) {
            $collection = Category::where('id', $search['category_id'])->with('children')->get();
            $flat = self::flatten($collection, 'children');
            $flat_ids = array_map(fn ($cat) => $cat->id, $flat);
            $flat_ids[] = $search['category_id'];
            unset($search['category_id']);
        }

        $query = $this->allQuery($search, $skip, $limit);

        /** search by TAG */

        if (isset($search) && isset($search['tag'])) {
            $query->whereHas('tags', function (Builder $query) use ($search) {
                $query->where('title', '=', $search['tag']);
            });
            unset($search['tag']);
        }


        if (isset($flat_ids)) {
            $query->whereIn('category_id', $flat_ids);
        }

        return $query->with('tags')->get($columns);
    }

    public function getStudentCourses(User $user): Collection
    {
        return DB::table('courses')
            ->select('courses.*', 'instructors.first_name', 'instructors.last_name')
            ->join('instructors', 'instructors.id', '=', 'courses.instructor_id')
            ->join('course_taken', 'course_taken.course_id', '=', 'courses.id')
            ->where('course_taken.user_id', $user->getKey())->get();
    }

    public function curriculum($id)
    {
        $sections = CurriculumSection::where('course_id', $id)->with(['lectures' => function ($query) {
            $query
                ->leftJoin('course_videos', 'course_videos.id', '=', 'curriculum_lectures_quiz.media')
                ->leftJoin('course_files', 'course_files.id', '=', 'curriculum_lectures_quiz.media');
        }])->get();
        return $sections;
    }

    public function inCategories(Collection $categories, ?int $skip = null, ?int $limit = null): Collection
    {
        return $this->inCategoriesQuery($categories, $skip, $limit)->get();
    }

    public function inCategoriesQuery(Collection $categories, ?int $skip = null, ?int $limit = null): Builder
    {
        return $this->allQuery([], $skip, $limit)
            ->whereIn('category_id', $categories)
            ->with('tags');
    }

    public function relatedMany(array $courses): Collection
    {
        $related = DB::table('courses_related')
            ->whereIn('course_id', $courses)
            ->inRandomOrder()
            ->limit(10)
            ->pluck('course_related_id')
            ->unique();

        return $this->model->newQuery()
            ->whereIn('id', $related)
            ->get();
    }

    public function popular(?int $skip = null, ?int $limit = null): Collection
    {
        return $this->allQuery([], $skip, $limit)
            ->withCount('users')
            ->orderBy('users_count', 'desc')
            ->get();
    }


    public function userOwnsCourse(Course $course, User $user): bool
    {
        return $user->courses()->where('courses.id', $course->getKey())->exists();
    }

    public function getByPopularity(PaginationDto $pagination, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $query = $this->model->newQuery()
            ->withCount(['users' => function ($q) use ($from, $to) {
                if (!is_null($from)) {
                    $q->where('course_user.created_at', '>=', $from);
                }

                if (!is_null($to)) {
                    $q->where('course_user.created_at', '<=', $to);
                }
            }]);

        if (!is_null($from)) {
            $query->where('courses.created_at', '>=', $from);
        }

        if (!is_null($to)) {
            $query->where('courses.created_at', '<=', $to);
        }

        $query->orderBy('users_count', 'DESC');

        $this->applyPaginationDto($query, $pagination);

        return $query->get();
    }

    public function isSuitCompleted(Course $course, User $user): bool
    {
        $completed = true;
        foreach ($course->category->courses as $course) {
            $courseProgressCollection = CourseProgressCollection::make($user, $course);
            if (!$courseProgressCollection->isFinished()) {
                $completed = false;
                break;
            }
        }
        return $completed;
    }

    public function countOfCompletedSuit(User $user): int
    {
        $count = 0;
        foreach ($user->courses()->distinct('courses.id')->get() as $course) {
            if ($this->isSuitCompleted($course, $user)) {
                $count++;
            }
        }
        return $count;
    }
}
