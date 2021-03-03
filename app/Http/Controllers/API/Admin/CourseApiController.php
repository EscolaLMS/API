<?php

namespace App\Http\Controllers\API\Admin;

use App\Dto\CurriculumLectureQuizDto;
use App\Dto\CurriculumSectionDto;
use App\Dto\ExternalResourceDto;
use App\Enum\CourseTypeEnum;
use App\Http\Requests\AudioUploadRequest;
use App\Http\Requests\DocumentUploadRequest;
use App\Http\Requests\LectureSortRequest;
use App\Http\Requests\SectionSortRequest;
use App\Http\Requests\VideoUploadRequest;
use App\Http\Resources\Course\CourseCurriculumResource;
use App\Http\Resources\Course\CourseSectionResource;
use App\Http\Resources\Course\FileResource;
use App\Http\Resources\Course\LectureCurriculumResource;
use App\Models\Course;
use App\Models\CourseFiles;
use App\Models\CourseVideos;
use App\Models\CurriculumLecturesQuiz;
use App\Models\CurriculumSection;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use EscolaSoft\EscolaLms\Http\Resources\Status;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseApiController
{
    private CourseServiceContract $courseService;

    /**
     * CourseCurriculumController constructor.
     * @param CourseServiceContract $courseService
     */
    public function __construct(CourseServiceContract $courseService)
    {
        $this->courseService = $courseService;
    }

    public function getSection(CurriculumSection $curriculumSection): JsonResponse
    {
        return (new CourseSectionResource($curriculumSection))->response();
    }

    public function storeSection(Request $request): JsonResponse
    {
        $sectionDto = CurriculumSectionDto::instantiateFromRequest($request);
        $section = $this->courseService->createSection($sectionDto);

        return (new CourseSectionResource($section))->response();
    }

    public function updateSection(CurriculumSection $curriculumSection, Request $request): JsonResponse
    {
        $sectionDto = CurriculumSectionDto::instantiateFromRequest($request);
        $section = $this->courseService->updateSection($sectionDto);

        return (new CourseSectionResource($section))->response();
    }

    public function deleteSection(CurriculumSection $curriculumSection, Request $request): JsonResponse
    {
        $this->courseService->deleteSection($curriculumSection);

        return (new Status(true))->response();
    }

    public function getLecture(CurriculumLecturesQuiz $curriculumLecturesQuiz): JsonResponse
    {
        return (new LectureCurriculumResource($curriculumLecturesQuiz))->response();
    }

    public function storeLecture(CurriculumSection $curriculumSection, Request $request): JsonResponse
    {
        $lectureDto = CurriculumLectureQuizDto::instantiateFromRequest($request);
        $lecture = $this->courseService->createLectureQuizRow($lectureDto);

        return (new LectureCurriculumResource($lecture))->response();
    }

    public function updateLecture(CurriculumSection $curriculumSection, CurriculumLecturesQuiz $curriculumLecturesQuiz, Request $request): JsonResponse
    {
        $lectureDto = CurriculumLectureQuizDto::instantiateFromRequestWithModel($request, $curriculumLecturesQuiz);
        $lecture = $this->courseService->updateLectureQuizRow($lectureDto, $curriculumLecturesQuiz);

        return (new LectureCurriculumResource($lecture))->response();
    }

    public function deleteLecture(CurriculumLecturesQuiz $curriculumLecturesQuiz, Request $request): JsonResponse
    {
        $this->courseService->deleteLectureQuiz($curriculumLecturesQuiz);

        return (new Status(true))->response();
    }

    public function sortSection(SectionSortRequest $request): JsonResponse
    {
        $this->courseService->sortSections($request->get('sectiondata', []));

        return (new Status(true))->response();
    }

    public function sortLecture(LectureSortRequest $request): JsonResponse
    {
        $this->courseService->sortLectures($request->input('lecturequizdata', []));

        return (new Status(true))->response();
    }

    public function postLectureDescSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, Request $request): JsonResponse
    {
        $this->courseService->storeLectureDesc($curriculumLecturesQuiz, $request->get('lecturedescription'));
        return new JsonResponse($curriculumLecturesQuiz->getKey());
    }

    public function removeVideo(CourseVideos $courseVideos): JsonResponse
    {
        try {
            $this->courseService->removeVideo($courseVideos);
            return (new Status(true))->response();
        } catch (\Exception $e) {
            return (new Status(false))->response()->setStatusCode(400);
        }
    }

    public function postLectureVideoSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, VideoUploadRequest $request): JsonResponse
    {
        try {
            return new JsonResponse($this->courseService->storeVideoLecture(
                $curriculumLecturesQuiz,
                $request->file('lecturevideo'),
                $request->user()
            )->getResource());
        } catch (\Exception $e) {
            return (new Status(false))->response();
        }
    }

    public function postLectureAudioSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, AudioUploadRequest $request): JsonResponse
    {
        try {
            return new JsonResponse(
                $this->courseService->storeLectureAudio(
                    $curriculumLecturesQuiz,
                    $request->file('lectureaudio'),
                    $request->user()
                )->getResource()
            );
        } catch (\Exception $e) {
            return (new Status(false))->response();
        }
    }

    public function postLectureDocumentSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, DocumentUploadRequest $request): JsonResponse
    {
        try {
            return new JsonResponse($this->courseService->storeLectureDocument(
                $curriculumLecturesQuiz,
                $request->file('lecturedoc'),
                $request->user()
            )->getResource());
        } catch (\Exception $e) {
            return (new Status(false))->response();
        }
    }

    public function postLectureTextSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, Request $request): JsonResponse
    {
        try {
            return new JsonResponse($this->courseService->storeLectureText(
                $curriculumLecturesQuiz,
                $request->get('lecturedescription', ''),
                $request->user()
            )->getResource());
        } catch (\Exception $e) {
            return (new Status(false))->response();
        }
    }

    public function postLectureH5PSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, Request $request): JsonResponse
    {
        try {
            return new JsonResponse($this->courseService->storeHP5Element(
                $curriculumLecturesQuiz,
                $request->get('media', ''),
                $request->user()
            )->getResource());
        } catch (\Exception $e) {
            return (new Status($e->getMessage()))->response();
        }
    }

    public function postLectureResourceSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, Request $request): JsonResponse
    {
        try {
            $file = $this->courseService->storeLectureResource($curriculumLecturesQuiz, $request->file('lectureres'), $request->user());
            return (new FileResource($file))->response();
        } catch (\Exception $e) {
            return (new Status(false))->response();
        }
    }

    public function deleteResource(CourseFiles $courseFiles): JsonResponse
    {
        try {
            $this->courseService->removeCourseFile($courseFiles);
            return (new Status(true))->response();
        } catch (\Exception $e) {
            return (new Status(false))->response();
        }
    }

    public function postLectureLibraryResourceSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, CourseFiles $courseFiles): JsonResponse
    {
        try {
            $file = $this->courseService->putLectureResource($curriculumLecturesQuiz, $courseFiles);
            return (new FileResource($file))->response();
        } catch (\Exception $e) {
            return (new Status(false))->response();
        }
    }

    public function postLectureExternalResourceSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, Request $request): JsonResponse
    {
        try {
            $externalResourceDto = ExternalResourceDto::instantiateFromRequest($request);
            $file = $this->courseService->storeExternalResource($curriculumLecturesQuiz, $externalResourceDto, $request->user());
            return (new FileResource($file))->response();
        } catch (\Exception $e) {
            return (new Status(false))->response();
        }
    }

    public function postLecturePublishSave(CurriculumLecturesQuiz $curriculumLecturesQuiz, Request $request): JsonResponse
    {
        $this->courseService->changeLectureStatus($curriculumLecturesQuiz, (bool)$request->input('publish'));
        return new JsonResponse($curriculumLecturesQuiz->getKey());
    }

    public function curriculum(Course $course, Request $request): JsonResponse
    {
        try {
            $courseContent = $this->courseService->getCourseContent($course);
            return (new CourseCurriculumResource($courseContent))->response();
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function postLectureLibrarySave(CurriculumLecturesQuiz $curriculumLecturesQuiz, int $fileId, Request $request): JsonResponse
    {
        // TODO: remove this if and refactor later with creating separate endpoint for each type

        if ($request->get('type') == CourseTypeEnum::VIDEO) {
            $courseFile = CourseVideos::find($fileId);
        } else {
            $courseFile = CourseFiles::find($fileId);
        }

        $data = $this->courseService->storeLectureLibrary(
            $curriculumLecturesQuiz->section->course->getKey(),
            $courseFile,
            $curriculumLecturesQuiz,
            $request->get('type')
        );

        return new JsonResponse($data);
    }
}
