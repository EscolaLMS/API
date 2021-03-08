<?php

namespace App\Http\Controllers\API;

use App\Dto\CourseSearchDto;
use App\Http\Controllers\API\Swagger\CourseSwagger;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\FileRequest;
use App\Http\Requests\OwnedCourseRequest;
use App\Http\Requests\RelatedManyCoursesRequest;
use App\Http\Resources\Course\CourseCurriculumResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Course\CoursesResource;
use App\Models\Category;
use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryContract;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use EscolaLms\Core\Http\Resources\Status;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * Class CourseAPIController
 * @package App\Http\Controllers\API
 */
class CourseAPIController extends AppBaseController implements CourseSwagger
{
    private CourseRepositoryContract $courseRepository;
    private CourseServiceContract $courseService;

    public function __construct(CourseRepositoryContract $courseRepository, CourseServiceContract $courseService)
    {
        $this->courseRepository = $courseRepository;
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the Courses.
     * GET|HEAD /courses
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): JsonResponse
    {
        $courses = $this->courseRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return (new CoursesResource($courses))->response();
    }

    /**
     * Display the specified course.
     * GET|HEAD /courses/{id}
     *
     * @param Course $course
     *
     * @return JsonResponse
     */
    public function show(Course $course): JsonResponse
    {
        try {
            $resource = (new CourseResource($this->courseService->getCourseContent($course)));
            return $resource->response();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified course curriculum.
     * GET|HEAD /courses/{id}/curriculum
     *
     * @param Course $course
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function curriculum(Course $course, OwnedCourseRequest $request): JsonResponse
    {
        try {
            $courseContent = $this->courseService->getCourseContent($course);
            return (new CourseCurriculumResource($courseContent))->response();
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function recommended(Request $request): JsonResponse
    {
        try {
            $courses = $this->courseService->recommended($request->user(), $request->get('skip'), $request->get('limit'));
            return (new CoursesResource($courses))->response();
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function related(Course $course, Request $request): JsonResponse
    {
        try {
            $courses = $this->courseService->related(
                $course,
                $request->input('skip'),
                $request->input('limit'),
            );

            return (new CoursesResource($courses))->response();
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function relatedMany(RelatedManyCoursesRequest $request): JsonResponse
    {
        try {
            $courses = $this->courseService->relatedMany($request->input('courses'));

            return (new CoursesResource($courses))->response();
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function popular(Request $request): JsonResponse
    {
        try {
            $courses = $this->courseService->popular(
                $request->get('skip'),
                $request->get('limit'),
            );

            return (new CoursesResource($courses))->response();
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }


    public function category(Request $request, Category $category): JsonResponse
    {
        $courseSearchDto = CourseSearchDto::instantiateFromRequest($request);
        $courses = $this->courseService->searchInCategory($courseSearchDto, $category);

        return (new CoursesResource($courses))->response();
    }




    public function file(FileRequest $request)
    {
        $path = $request->input('path');

        return response(Storage::get($path))
            ->header('Content-Type', Storage::mimeType($path));
    }
}
