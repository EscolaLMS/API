<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Requests\API\CourseProgressAPIRequest;
use App\Models\Course;
use App\Models\CurriculumLecturesQuiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface CourseProgressSwagger
{
    /**
     * @OA\Get(
     *      tags={"progress"},
     *      path="/api/courses/progress",
     *      description="Get Progresses",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Bad request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function index(Request $request): JsonResponse;

    /**
     * @OA\Get(
     *      tags={"progress"},
     *      path="/api/courses/progress/{id}",
     *      description="Show user course progress",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="number",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Bad request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function show(Course $course, Request $request): JsonResponse;

    /**
     * @OA\Patch(
     *      tags={"progress"},
     *      path="/api/courses/progress/{id}",
     *      description="Show user course progress",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="number",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          in="query",
     *          name="progress[]",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(
     *                      property="lecture_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      type="integer"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Bad request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function store(Course $course, CourseProgressAPIRequest $request): JsonResponse;

    /**
     * @OA\Put(
     *      tags={"progress"},
     *      path="/api/courses/progress/{id}/ping",
     *      description="Update time in course by ping.",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="number",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Bad request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function ping(CurriculumLecturesQuiz $curriculum_lectures_quiz, Request $request): JsonResponse;

    /**
     * @OA\Post(
     *      tags={"progress"},
     *      path="/api/courses/progress/{id}/h5p",
     *      description="Update h5p progress in course quiz.",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="number",
     *          ),
     *      ),
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="event",
     *                 type="string",
     *                 example="http://adlnet.gov/expapi/verbs/attempted",
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *             ),
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Bad request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function h5p(CurriculumLecturesQuiz $curriculum_lectures_quiz, Request $request): JsonResponse;
}
