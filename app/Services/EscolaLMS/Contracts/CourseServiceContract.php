<?php


namespace App\Services\EscolaLMS\Contracts;

use App\Dto\CourseCreateDto;
use App\Dto\CourseImageDto;
use App\Dto\CourseListDto;
use App\Dto\CourseSearchDto;
use App\Dto\CourseViewDto;
use App\Dto\CurriculumLectureQuizDto;
use App\Dto\CurriculumSectionDto;
use App\Dto\ExternalResourceDto;
use App\Dto\FilterCourseListDto;
use App\Models\Category;
use App\Models\Contracts\MediaModelContract;
use App\Models\Course;
use App\Models\CourseFiles;
use App\Models\CourseVideos;
use App\Models\CurriculumLecturesQuiz;
use App\Models\CurriculumSection;
use App\Models\User;
use App\Services\EscolaLMS\Media\Media;
use App\ValueObjects\CourseContent;
use App\ValueObjects\UserFiles;
use EscolaSoft\EscolaLms\Dtos\PaginationDto;
use EscolaSoft\EscolaLms\Dtos\PeriodDto;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface CourseServiceContract
{
    public function getCourseView(string $course_slug): CourseViewDto;

    public function getCourses(FilterCourseListDto $filterCourseListDto): CourseListDto;

    public function getCoursesListAsInstructor(Authenticatable $user, ?string $search = null): LengthAwarePaginator;

    public function getCoursesNotRelated(Course $course, ?string $search = null): LengthAwarePaginator;

    public function getCoursesListByCriteria(array $criteria): LengthAwarePaginator;

    public function saveBreadcrumb(?string $link): void;

    public function courseFeaturedImageSave(CourseImageDto $courseImageDto): Course;

    public function storeLectureDesc(CurriculumLecturesQuiz $curriculumLecturesQuiz, string $description): CurriculumLecturesQuiz;

    public function createSection(CurriculumSectionDto $curriculumSectionDto): CurriculumSection;

    public function updateSection(CurriculumSectionDto $curriculumSectionDto): CurriculumSection;

    public function deleteSection(CurriculumSection $curriculumSection): void;

    public function createLectureQuizRow(CurriculumLectureQuizDto $curriculumLectureQuizDto): CurriculumLecturesQuiz;

    public function updateLectureQuizRow(CurriculumLectureQuizDto $curriculumLectureQuizDto, CurriculumLecturesQuiz $curriculumLecturesQuiz): CurriculumLecturesQuiz;

    public function deleteLectureQuiz(CurriculumLecturesQuiz $curriculumLecturesQuiz): void;

    public function deleteLectureResource(int $lectureId, int $resourceId): void;

    public function storeVideoLecture(CurriculumLecturesQuiz $curriculumLecturesQuiz, UploadedFile $video, Authenticatable $user): Media;

    public function storeVideoForCourse(UploadedFile $video, Course $course, User $user): CourseVideos;

    public function storeAudioForCourse(UploadedFile $audio, Course $course, User $user): CourseFiles;

    public function storeDocumentForCourse(UploadedFile $document, Course $course, User $user): CourseFiles;

    public function storeResourceForCourse(UploadedFile $resource, Course $course, User $user): CourseFiles;

    public function storeLectureAudio(CurriculumLecturesQuiz $curriculumLecturesQuiz, UploadedFile $audio, Authenticatable $user): Media;

    public function storeLectureLibrary(int $courseId, ?MediaModelContract $courseFile, CurriculumLecturesQuiz $curriculumLecturesQuiz, int $type): array;

    public function storeLectureResource(CurriculumLecturesQuiz $curriculumLecturesQuiz, UploadedFile $document, Authenticatable $user): CourseFiles;

    public function storeExternalResource(CurriculumLecturesQuiz $curriculumLecturesQuiz, ExternalResourceDto $externalResource, Authenticatable $user): CourseFiles;

    public function changeLectureStatus(CurriculumLecturesQuiz $curriculumLecturesQuiz, bool $publish): void;

    public function createCourse(CourseCreateDto $payload): Course;

    public function attachVideo(UploadedFile $video, Course $course, Authenticatable $user): void;

    public function getCourseContent(Course $course): CourseContent;

    public function getUserFiles(User $user): UserFiles;

    public function findCourseFile(int $fileId): ?CourseFiles;

    public function getLectureQuizzes($section): Collection;

    public function removeVideo(CourseVideos $courseVideos): void;

    public function putLectureResource(CurriculumLecturesQuiz $curriculumLecturesQuiz, MediaModelContract $file): MediaModelContract;

    public function recommended(Authenticatable $user, ?int $skip, ?int $limit): Collection;

    public function related(Course $course, ?int $skip, ?int $limit): Collection;

    public function popular(?int $skip = null, ?int $limit = null): Collection;

    public function search(CourseSearchDto $courseSearchDto): LengthAwarePaginator;

    public function relatedMany(array $courses): Collection;

    public function searchInCategory(CourseSearchDto $courseSearchDto, Category $category): LengthAwarePaginator;

    public function removeCourseFile(CourseFiles $file): void;

    public function getPopular(PaginationDto $pagination, PeriodDto $period): Collection;

    public function attachOrderedCoursesToUser(Collection $courses, Authenticatable $user): void;
}
