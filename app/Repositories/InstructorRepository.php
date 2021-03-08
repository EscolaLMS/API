<?php

namespace App\Repositories;

use EscolaLms\Core\Enums\UserRole;
use App\Models\Course;
use App\Models\Credit;
use App\Models\Instructor;
use App\Models\User;
use App\Models\WithdrawRequest;
use App\Repositories\Contracts\InstructorRepositoryContract;
use EscolaLms\Core\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

/**
 * Class InstructorRepository
 * @package App\Repositories
 * @version December 7, 2020, 1:50 pm UTC
 */
class InstructorRepository extends BaseRepository implements InstructorRepositoryContract
{
    public const DEFAULT_IMAGE_PATH = 'images/instructor.jpg';

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'first_name',
        'last_name',
        'instructor_slug',
        'contact_email',
        'telephone',
        'mobile',
        'paypal_id',
        'biography',
        'instructor_image'
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
        return Instructor::class;
    }

    public function findBySlug(string $slug): Instructor
    {
        return $this->model->newQuery()->where('instructor_slug', $slug)->firstOrFail();
    }

    public function queryAll(): Builder
    {
        return $this->model->newQuery()
            ->select('courses.*', 'categories.name as category_name')
            ->leftJoin('categories', 'categories.id', '=', 'courses.category_id');
    }

    public function getMetrics(int $instructor_id): array
    {
        $metrics = [];
        $metrics['courses'] = Course::query()->where('instructor_id', $instructor_id)->count();
        $metrics['lectures'] = Course::query()
            ->where('courses.instructor_id', $instructor_id)
            ->leftJoin('curriculum_sections', 'curriculum_sections.course_id', '=', 'courses.id')
            ->leftJoin('curriculum_lectures_quiz', 'curriculum_lectures_quiz.section_id', '=', 'curriculum_sections.section_id')
            ->count();
        $metrics['videos'] = Course::query()
            ->where('courses.instructor_id', $instructor_id)
            ->where('curriculum_lectures_quiz.media_type', 0)
            ->leftJoin('curriculum_sections', 'curriculum_sections.course_id', '=', 'courses.id')
            ->leftJoin('curriculum_lectures_quiz', 'curriculum_lectures_quiz.section_id', '=', 'curriculum_sections.section_id')
            ->count();
        return $metrics;
    }

    public function getInstructorCourses(int $instructorId): LengthAwarePaginator
    {
        return Course::where('courses.instructor_id', $instructorId)
            ->select('courses.*', 'categories.name as category_name')
            ->leftJoin('categories', 'categories.id', '=', 'courses.category_id')
            ->paginate(config('app.paginate_count'));
    }

    public function getInstructorCredits(int $instructorId): LengthAwarePaginator
    {
        return Credit::where('instructor_id', $instructorId)
            ->where('credits_for', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.paginate_count'));
    }

    public function getInstructorWithdrawRequests(int $instructorId): LengthAwarePaginator
    {
        return WithdrawRequest::where('instructor_id', $instructorId)->paginate(config('app.paginate_count'));
    }

    public function adminMetrics(): array
    {
        $metrics = [];
        $metrics['courses'] = Course::count();
        $metrics['students'] = Role::whereName(UserRole::STUDENT)->first()->users()->count();
        $metrics['instructors'] = Role::whereName(UserRole::INSTRUCTOR)->first()->users()->count();
        return $metrics;
    }

    public function createUsingUser(User $user): Instructor
    {
        return Instructor::create([
            'user_id' => $user->getKey(),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'instructor_slug' => \App\Library\EscolaHelpers::slugify($user->first_name . ' ' . $user->last_name),
            'contact_email' => $user->email,
            'instructor_image' => self::DEFAULT_IMAGE_PATH
        ]);
    }
}
