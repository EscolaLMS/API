<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Requests\OwnedCourseRequest;
use App\Http\Requests\RelatedManyCoursesRequest;
use EscolaLms\Categories\Models\Category;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface CourseSwagger
{
    /**
     * @OA\Get(
     *      tags={"Courses"},
     *      path="/api/courses",
     *      description="Get Courses",
     *      operationId="getCoursesList",
     *      @OA\Parameter(
     *          name="course_title",
     *          description="Course name %LIKE%",
     *          required=false,
     *          in="query",
     *          example="Photography",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="category_id",
     *          description="Category ID. When applied all courses with given cat_id and children categories are searched",
     *          required=false,
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="number",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="tag",
     *          description="Tag name exactly",
     *          required=false,
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="string",
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
    public function index(Request $request): JsonResponse;

    /**
     * @OA\Get(
     *      path="/api/courses/recommended",
     *      description="Get recommended Courses",
     *      tags={"Courses"},
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
    public function recommended(Request $request): JsonResponse;

    /**
     * @OA\Get(
     *      path="/api/courses/popular",
     *      description="Get frequently bought Courses",
     *      tags={"Courses"},
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
    public function popular(Request $request): JsonResponse;

    /**
     * @OA\Get(
     *      path="/api/courses/related/{id}",
     *      description="Get related Courses",
     *      tags={"Courses"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Course ID passing for fetch related courses or if there is no related, similar courses based on categories (including parent)",
     *          required=false,
     *          in="path",
     *          example="1",
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
    public function related(Course $course, Request $request): JsonResponse;

    /**
     * @OA\Get(
     *      path="/api/courses/related-many",
     *      description="Get related Courses for many others",
     *      tags={"Courses"},
     *      @OA\Parameter(
     *          name="courses[]",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Schema(
     *                      type="integer",
     *                  )
     *              ),
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
    public function relatedMany(RelatedManyCoursesRequest $request): JsonResponse;

    /**
     * @OA\Get(
     *      tags={"Courses"},
     *      path="/api/courses/{id}",
     *      description="Fetch the specified Course",
     *      operationId="getCourse",
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
     *          response=404,
     *          description="Not found request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function show(Course $course): JsonResponse;

    /**
     * @OA\Get(
     *      tags={"Courses"},
     *      path="/api/courses/{id}/curriculum",
     *      description="Fetch the specified Course curriculum",
     *      operationId="getCourseCurriculum",
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
     *          response=404,
     *          description="Not found request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function curriculum(Course $course, OwnedCourseRequest $request): JsonResponse;






    /**
     * @OA\Get(
     *      tags={"Courses"},
     *      path="/api/courses/category/{category_id}",
     *      description="Searche Course By Criteria",
     *      operationId="searchCourseByCategory",
     *      @OA\Parameter(
     *          name="category_id",
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
    public function category(Request $request, Category $category): JsonResponse;



}
