<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Swagger\CourseProgressSwagger;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CourseProgressAPIRequest;
use App\Http\Requests\API\CreateCourseProgressAPIRequest;
use App\Http\Requests\API\CourseH5pProgressAPIRequest;
use App\Http\Resources\Course\ProgressesResource;
use App\Models\Course;
use App\Models\CurriculumLecturesQuiz;
use App\Repositories\CourseProgressRepository;
use App\Repositories\CourseH5PProgressRepository;
use App\Services\Contracts\ProgressServiceContract;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use App\ValueObjects\CourseProgressCollection;
use EscolaSoft\EscolaLms\Http\Resources\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;

/**
 * Class CourseProgressController
 * @package App\Http\Controllers\API
 */
class CourseProgressAPIController extends AppBaseController implements CourseProgressSwagger
{
    private CourseServiceContract $courseService;
    private ProgressServiceContract $progressService;
    private CourseProgressRepository $courseProgressRepository;

    public function __construct(
        CourseServiceContract $courseService,
        ProgressServiceContract $progressService,
        CourseProgressRepository $courseProgressRepo
    ) {
        $this->courseService = $courseService;
        $this->progressService = $progressService;
        $this->courseProgressRepository = $courseProgressRepo;
    }

    /**
     * Display a listing of the CourseProgress.
     * GET|HEAD /course/progress
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return (new ProgressesResource($this->progressService->getByUser($request->user())))->response();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified CourseProgress.
     * GET|HEAD /course/progress/{course}
     *
     * @param Course $course
     *
     * @return Response
     */
    public function show(Course $course, Request $request): JsonResponse
    {
        try {
            return new JsonResponse(CourseProgressCollection::make($request->user(), $course)->getProgress());
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified CourseProgress in storage.
     * PATCH /course/progress/{id}
     *
     * @param Course $course
     * @param CourseProgressAPIRequest $request
     *
     * @return Response
     */
    public function store(Course $course, CourseProgressAPIRequest $request): JsonResponse
    {
        try {
            $progress = $this->progressService->update($course, $request->user(), $request->get('progress'));

            return new JsonResponse($progress->getProgress());
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    /**
     * Saves CourseH5PProgress in storage.
     * POST /course/progress/{id}/h5p
     *
     * @param Course $course
     * @param CourseH5pProgressAPIRequest $request
     *
     * @return Response
     */
    public function h5p(CurriculumLecturesQuiz $curriculum_lectures_quiz, Request $request): JsonResponse
    {
        try {
            $this->progressService->h5p($request->user(), $curriculum_lectures_quiz, $request->input('event'), $request->input('data'));
            return (new Status(true))->response();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function ping(CurriculumLecturesQuiz $curriculum_lectures_quiz, Request $request): JsonResponse
    {
        try {
            $this->progressService->ping($request->user(), $curriculum_lectures_quiz);
            return (new Status(true))->response();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }
}
