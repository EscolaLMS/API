<?php

namespace App\Services\EscolaLMS;

use App\Dto\CourseCreateDto;
use App\Dto\CourseFileDto;
use App\Dto\CourseImageDto;
use App\Dto\CourseListDto;
use App\Dto\CourseSearchDto;
use App\Dto\CourseUpdateDto;
use App\Dto\CourseVideoDto;
use App\Dto\CourseViewDto;
use App\Dto\CurriculumLectureQuizDto;
use App\Dto\CurriculumSectionDto;
use App\Dto\ExternalResourceDto;
use App\Dto\FilterCourseListDto;
use App\Enum\CourseTypeEnum;
use App\Enum\InstructionLevel;
use App\Enum\LectureType;
use App\Enum\MediaType;
use EscolaLms\Core\Enums\UserRole;
use App\Library\VideoHelpers;
use App\Models\Category;
use App\Models\Contracts\MediaModelContract;
use App\Models\Course;
use App\Models\CourseFiles;
use App\Models\CourseVideos;
use App\Models\CurriculumLecturesQuiz;
use App\Models\CurriculumSection;
use App\Models\User;
use App\Repositories\Contracts\CourseFileRepositoryContract;
use App\Repositories\Contracts\CourseRepositoryContract;
use App\Repositories\Contracts\CourseVideoRepositoryContract;
use App\Repositories\Contracts\CurriculumLecturesQuizRepositoryContract;
use App\Repositories\Contracts\CurriculumSectionRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Criteria\CourseInCategory;
use App\Repositories\Criteria\CourseInstructor;
use EscolaLms\Core\Repositories\Criteria\CourseSearch;
use EscolaLms\Core\Repositories\Criteria\NotRelatedCourses;
use EscolaLms\Core\Repositories\Criteria\Primitives\InCriterion;
use EscolaLms\Core\Repositories\Criteria\UserCriterion;
use App\Services\Contracts\VideoServiceContract;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use App\Services\EscolaLMS\Media\Media;
use App\ValueObjects\CourseContent;
use App\ValueObjects\CourseProgressCollection;
use App\ValueObjects\UserFiles;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Dtos\PeriodDto;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CourseService implements CourseServiceContract
{
    private Course $model;
    private CourseRepositoryContract $courseRepository;
    private VideoServiceContract $videoService;
    private CourseVideoRepositoryContract $courseVideoRepository;
    private CurriculumSectionRepositoryContract $curriculumSectionRepository;
    private CurriculumLecturesQuizRepositoryContract $curriculumLecturesQuizRepository;
    private CourseFileRepositoryContract $courseFileRepository;
    private UserRepositoryContract $userRepository;

    /**
     * CourseService constructor.
     * @param Course $course
     * @param VideoServiceContract $videoService
     * @param CourseRepositoryContract $courseRepository
     * @param CourseVideoRepositoryContract $courseVideoRepository
     * @param CurriculumSectionRepositoryContract $curriculumSectionRepository
     * @param CurriculumLecturesQuizRepositoryContract $curriculumLecturesQuizRepository
     * @param CourseFileRepositoryContract $courseFileRepository
     * @param UserRepositoryContract $userRepository
     */
    public function __construct(
        Course $course,
        VideoServiceContract $videoService,
        CourseRepositoryContract $courseRepository,
        CourseVideoRepositoryContract $courseVideoRepository,
        CurriculumSectionRepositoryContract $curriculumSectionRepository,
        CurriculumLecturesQuizRepositoryContract $curriculumLecturesQuizRepository,
        CourseFileRepositoryContract $courseFileRepository,
        UserRepositoryContract $userRepository
    )
    {
        $this->model = $course;
        $this->videoService = $videoService;
        $this->courseRepository = $courseRepository;
        $this->courseVideoRepository = $courseVideoRepository;
        $this->curriculumSectionRepository = $curriculumSectionRepository;
        $this->curriculumLecturesQuizRepository = $curriculumLecturesQuizRepository;
        $this->courseFileRepository = $courseFileRepository;
        $this->userRepository = $userRepository;
    }

    public function getCourseView(string $course_slug): CourseViewDto
    {
        $course = Course::where('course_slug', $course_slug)->first();

        $curriculum = $this->model->getcurriculum($course->getKey(), $course_slug);

        return new CourseViewDto($course, $curriculum);
    }

    public function getStudentCourses(User $user)
    {
        return $this->courseRepository->getStudentCourses($user);
    }

    public function getCourses(FilterCourseListDto $filterCourseListDto): CourseListDto
    {
        $paginate_count = config('app.paginate_count');
        $categories = Category::where('is_active', 1)->get();
        $instruction_levels = InstructionLevel::getDetails();

        $query = DB::table('courses')
            ->select('courses.*', 'instructors.first_name', 'instructors.last_name')
            ->join('instructors', 'instructors.id', '=', 'courses.instructor_id')
            ->where('courses.is_active', 1);

        //filter categories as per user selected
        if ($filterCourseListDto->getCategorySearch()) {
            $query->whereIn('courses.category_id', $filterCourseListDto->getCategorySearch());
        }
        //filter courses as per keyword
        if ($filterCourseListDto->getKeyword()) {
            $query->where('courses.course_title', 'LIKE', '%' . $filterCourseListDto->getKeyword() . '%');
        }

        //filter instruction levels as per user selected
        if ($filterCourseListDto->getInstructionLevelId()) {
            $query->whereIn('courses.instruction_level_id', $filterCourseListDto->getInstructionLevelId());
        }

        //filter price as per user selected
        if ($filterCourseListDto->getPrices()) {
            $price_count = count($filterCourseListDto->getPrices());
            $is_greater_500 = false;

            foreach ($filterCourseListDto->getPrices() as $p => $price) {
                $p++;
                $price_split = explode('-', $price);

                if ($price_count == 1) {
                    $from = $price_split[0];
                    if ($price == 500) {
                        $is_greater_500 = true;
                    } else {
                        $to = $price_split[1];
                    }
                } elseif ($p == 1) {
                    $from = $price_split[0];
                } elseif ($p == $price_count) {
                    if ($price == 500) {
                        $is_greater_500 = true;
                    } else {
                        $to = $price_split[1];
                    }
                }
            }
            $query->where('courses.price', '>=', $from);
            if (!$is_greater_500) {
                $query->where('courses.price', '<=', $to);
            }
        }

        //filter categories as per user selected
        if ($filterCourseListDto->getSortPrice()) {
            $query->orderBy('courses.price', $filterCourseListDto->getSortPrice());
        }

        $courses = $query->groupBy('courses.id')->paginate($paginate_count);

        return new CourseListDto($courses, $categories, $instruction_levels);
    }

    public function getCoursesListAsInstructor(Authenticatable $user, ?string $search = null): LengthAwarePaginator
    {
        $criteria = [];

        if (!$user->hasRole(UserRole::ADMIN)) {
            $criteria[] = new CourseInstructor($user->instructor);
        }

        if ($search) {
            $criteria[] = new CourseSearch($search);
        }

        return $this->getCoursesListByCriteria($criteria);
    }

    public function getCoursesNotRelated(Course $course, ?string $search = null): LengthAwarePaginator
    {
        $criteria = [
            new NotRelatedCourses('id', $course),
        ];

        if ($search) {
            $criteria[] = new CourseSearch($search);
        }

        return $this->getCoursesListByCriteria($criteria);
    }

    public function getCoursesListByCriteria(array $criteria): LengthAwarePaginator
    {
        $query = $this->courseRepository->queryAll()->orderBy('id', 'desc');

        return $this->courseRepository
            ->applyCriteria($query, $criteria)
            ->paginate(config('app.paginate_count'));
    }

    public function saveBreadcrumb(?string $link): void
    {
        Session::put('course_breadcrumb', $link);
        Session::save();
    }

    public function courseFeaturedImageSave(CourseImageDto $courseImageDto): Course
    {
        $this->removeExisting($courseImageDto->getOldCourseImage());
        $this->removeExisting($courseImageDto->getOldThumbImage());

        $file_name = $courseImageDto->getCourseImage()->getClientOriginalName();

        $image_make = Image::make($courseImageDto->getCourseImageBase64())->encode('jpg');

        // create path
        $path = "course/" . $courseImageDto->getCourse()->getKey();

        //check if the file name is already exists
        $new_file_name = \SiteHelpers::checkFileName($path, $file_name);

        //save the image using storage
        Storage::put($path . "/" . $new_file_name, $image_make->__toString(), 'public');

        //resize image for thumbnail
        $thumb_image = "thumb_" . $new_file_name;
        $resize = Image::make($courseImageDto->getCourseImageBase64())->resize(258, 172)->encode('jpg');
        Storage::put($path . "/" . $thumb_image, $resize->__toString(), 'public');

        $course = $courseImageDto->getCourse();
        $course->course_image = $path . "/" . $new_file_name;
        $course->thumb_image = $path . "/" . $thumb_image;
        $course->save();

        return $course;
    }

    public function storeLectureDesc(CurriculumLecturesQuiz $curriculumLecturesQuiz, string $description): CurriculumLecturesQuiz
    {
        $curriculumLectureQuizDto = new CurriculumLectureQuizDto(
            $curriculumLecturesQuiz->section,
            $curriculumLecturesQuiz->type,
            $curriculumLecturesQuiz->sort_order,
            $curriculumLecturesQuiz->title,
            $description,
            $curriculumLecturesQuiz->contenttext,
            $curriculumLecturesQuiz->media,
            $curriculumLecturesQuiz->media_type,
            $curriculumLecturesQuiz->resources,
            $curriculumLecturesQuiz->publish,
            $curriculumLecturesQuiz->duration
        );

        return $this->updateLectureQuizRow($curriculumLectureQuizDto, $curriculumLecturesQuiz);
    }

    public function createSection(CurriculumSectionDto $curriculumSectionDto): CurriculumSection
    {
        $curriculumSection = $this->curriculumSectionRepository->createUsingDto($curriculumSectionDto);
        $this->uploadImage($curriculumSection, $curriculumSectionDto, 'section');
        return $curriculumSection;
    }

    public function updateSection(CurriculumSectionDto $curriculumSectionDto): CurriculumSection
    {
        /** TODO: should return `image` URL  */
        $curriculumSection = $this->curriculumSectionRepository->updateSection($curriculumSectionDto);
        $this->uploadImage($curriculumSection, $curriculumSectionDto, 'section');
        return $curriculumSection;
    }

    public function deleteSection(CurriculumSection $curriculumSection): void
    {
        $this->curriculumSectionRepository->delete($curriculumSection->getKey());
    }

    public function createLectureQuizRow(CurriculumLectureQuizDto $curriculumLectureQuizDto): CurriculumLecturesQuiz
    {
        $curriculumLecture = $this->curriculumLecturesQuizRepository->create($curriculumLectureQuizDto->toArray());
        $this->uploadImage($curriculumLecture, $curriculumLectureQuizDto, 'lecture');
        return $curriculumLecture;
    }

    public function updateLectureQuizRow(CurriculumLectureQuizDto $curriculumLectureQuizDto, CurriculumLecturesQuiz $curriculumLecturesQuiz): CurriculumLecturesQuiz
    {
        $payload = [];

        foreach ($curriculumLectureQuizDto->toArray() as $key => $value) {
            $payload[$key] = $value;
        }

        $this->uploadImage($curriculumLecturesQuiz, $curriculumLectureQuizDto, 'lecture');

        return $this->curriculumLecturesQuizRepository->update($payload, $curriculumLecturesQuiz->getKey());
    }

    public function deleteLectureQuiz(CurriculumLecturesQuiz $curriculumLecturesQuiz): void
    {
        $this->curriculumLecturesQuizRepository->delete($curriculumLecturesQuiz->getKey());
    }

    public function deleteLectureResource(int $lectureId, int $resourceId): void
    {
        $this->model->postLectureResourceDelete($lectureId, $resourceId);
    }

    public function storeVideoLecture(CurriculumLecturesQuiz $curriculumLecturesQuiz, UploadedFile $video, Authenticatable $user): Media
    {
        $curriculumLecturesQuiz->media_type = MediaType::VIDEO;
        return Media::make($curriculumLecturesQuiz)->create($video, $user);
    }

    public function storeLectureAudio(CurriculumLecturesQuiz $curriculumLecturesQuiz, UploadedFile $audio, Authenticatable $user): Media
    {
        $curriculumLecturesQuiz->media_type = MediaType::AUDIO;
        return Media::make($curriculumLecturesQuiz)->create($audio, $user);
    }

    public function storeLectureDocument(CurriculumLecturesQuiz $curriculumLecturesQuiz, UploadedFile $document, Authenticatable $user): Media
    {
        $curriculumLecturesQuiz->media_type = MediaType::DOCUMENT;
        return Media::make($curriculumLecturesQuiz)->create($document, $user);
    }

    public function storeLectureText(CurriculumLecturesQuiz $curriculumLecturesQuiz, string $text, Authenticatable $user): Media
    {
        $curriculumLecturesQuiz->media_type = MediaType::TEXT;
        return Media::make($curriculumLecturesQuiz)->create($text, $user);
    }

    public function storeHP5Element(CurriculumLecturesQuiz $curriculumLecturesQuiz, string $text, Authenticatable $user): Media
    {
        $curriculumLecturesQuiz->media_type = MediaType::INTERACTIVE;
        return Media::make($curriculumLecturesQuiz)->create($text, $user);
    }

    public function storeLectureLibrary(int $courseId, ?MediaModelContract $courseFile, CurriculumLecturesQuiz $curriculumLecturesQuiz, int $type): array
    {
        $curriculumLectureQuizDto = new CurriculumLectureQuizDto(
            $curriculumLecturesQuiz->section,
            $curriculumLecturesQuiz->type ?? LectureType::SECTION,
            $curriculumLecturesQuiz->sort_order ?? 0,
            $curriculumLecturesQuiz->title,
            $curriculumLecturesQuiz->description,
            $curriculumLecturesQuiz->contenttext,
            $courseFile->getKey(),
            $type,
            $curriculumLecturesQuiz->resources,
            $curriculumLecturesQuiz->publish ?? true,
        );
        $this->updateLectureQuizRow($curriculumLectureQuizDto, $curriculumLecturesQuiz);

        // TODO: in future we should consider split this into strategies and make it extendable
        // TODO [2]: we are using Media class now, so any handlers should be refactored there
        if ($type == CourseTypeEnum::VIDEO) {
            $file_title = $courseFile->video_name;
            $duration = $courseFile->duration;
            $processed = $courseFile->processed;
            if ($processed == 1) {
                $file_link = Storage::url('course/' . $courseId . '/' . $courseFile->video_title . '.webm');
            } else {
                $file_link = '';
            }
        } elseif ($type == CourseTypeEnum::TO_REFACTOR_1) {
            $file_title = $courseFile->file_title;
            $duration = $courseFile->duration;
            $file_link = Storage::url('course/' . $courseId . '/' . $courseFile->file_name . '.' . $courseFile->file_extension);
        } elseif ($type == CourseTypeEnum::TO_REFACTOR_2 || $type == CourseTypeEnum::TO_REFACTOR_5) {
            $file_title = $courseFile->file_title;
            if ($courseFile->duration <= 1) {
                $pdfPage = $courseFile->duration . ' Page';
            } else {
                $pdfPage = $courseFile->duration . ' Pages';
            }
            $duration = $pdfPage;
            $file_link = '';
        }

        return [
            'status' => true,
            'duration' => $duration,
            'file_title' => $file_title,
            'file_link' => $file_link
        ];
    }

    public function storeLectureResource(CurriculumLecturesQuiz $curriculumLecturesQuiz, UploadedFile $document, Authenticatable $user): CourseFiles
    {
        $courseFile = $this->storeResourceForCourse($document, $curriculumLecturesQuiz->section->course, $user);

        return $this->putLectureResource($curriculumLecturesQuiz, $courseFile);
    }

    public function storeExternalResource(CurriculumLecturesQuiz $curriculumLecturesQuiz, ExternalResourceDto $externalResource, Authenticatable $user): CourseFiles
    {
        $courseFileDto = new CourseFileDto($externalResource->getLink(), $externalResource->getTitle(), 'link', 'link', 0, 'curriculum_resource_link', '', $user, $curriculumLecturesQuiz->section->course, true);

        $courseFile = $this->courseFileRepository->create($courseFileDto->toArray());

        $this->putLectureResource($curriculumLecturesQuiz, $courseFile);

        return $courseFile;
    }

    public function changeLectureStatus(CurriculumLecturesQuiz $curriculumLecturesQuiz, bool $publish): void
    {
        $this->curriculumLecturesQuizRepository->setStatus($curriculumLecturesQuiz, $publish);
    }

    public function createCourse(CourseCreateDto $payload): Course
    {
        return $this->courseRepository->create($payload->toArray());
    }

    public function updateCourse(CourseUpdateDto $payload, Course $course): Course
    {
        return $this->courseRepository->update($payload->toArray(), $course->getKey());
    }

    public function attachVideo(UploadedFile $video, Course $course, Authenticatable $user): void
    {
        $this->removeCoursePromoVideo($course);

        $courseVideos = $this->storeVideoForCourse($video, $course, $user);

        $course->course_video = $courseVideos->getKey();
        $course->save();
    }

    public function getCourseContent(Course $course): CourseContent
    {
        $course = $this->initializeWhenEmpty($course);
        return CourseContent::make($course);
    }

    public function getUserFiles(User $user): UserFiles
    {
        $uploaderCriterion = new UserCriterion('uploader_id', $user);

        $videos = $this->courseVideoRepository->searchByCriteria([$uploaderCriterion]);
        $files = $this->courseFileRepository->searchByCriteria([
            $uploaderCriterion,
            new InCriterion('file_extension', ['mp3', 'wav', 'pdf', 'doc', 'docx']),
            new InCriterion('file_tag', ['curriculum_presentation', 'curriculum', 'curriculum_resource'])
        ]);

        return UserFiles::make($videos, $files);
    }

    public function getLectureQuizzes($section): Collection
    {
        $lecturesQuiz = $section->lectures()->orderBy('sort_order', 'asc')->get();

        if ($lecturesQuiz->isEmpty()) {
            $curriculumLectureQuizDto = new CurriculumLectureQuizDto($section, LectureType::SECTION, 1, 'Introduction');

            return new Collection([$this->createLectureQuizRow($curriculumLectureQuizDto)]);
        }

        return $lecturesQuiz;
    }

    public function findCourseFile(int $fileId): ?CourseFiles
    {
        return $this->courseFileRepository->find($fileId);
    }

    public function sortSections(array $sections): void
    {
        if (!empty($sections)) {
            $this->curriculumSectionRepository->sort($sections);
        }
    }

    public function sortLectures(array $lectures): void
    {
        if (!empty($lectures)) {
            $this->curriculumLecturesQuizRepository->sort($lectures);
        }
    }

    /**
     * @param UploadedFile $video
     * @return string
     */
    private function getVideoFileName(UploadedFile $video): string
    {
        $file_name = explode('.', $video->getClientOriginalName());
        $file_name = $file_name[0] . '_' . time() . rand(4, 9999);
        $file_name = str_slug($file_name, "-");
        return $file_name;
    }

    /**
     * @param UploadedFile $video
     * @param int $courseId
     * @param User $user
     * @return CourseVideos
     */
    public function storeVideoForCourse(UploadedFile $video, Course $course, User $user): CourseVideos
    {
        $file_name = $this->getVideoFileName($video);
        $extension = $video->getClientOriginalExtension();
        $file_title = $video->getClientOriginalName();

        $this->videoService->setCredentials($video->getPathName(), $file_name);
        $duration = $this->videoService->getDuration();

        $path = 'course/' . $course->getKey();
        $video_name = 'raw_' . $file_name . '.' . $extension;
        $video->storeAs($path, $video_name);

        $video_image_name = 'raw_' . $file_name . '.jpg';
        $video_image_path = storage_path('app/public/' . $path . '/' . $video_image_name);
        $this->videoService->convertImages($video_image_path);

        $courseVideoDto = new CourseVideoDto(
            'raw_' . $file_name,
            $file_title,
            $extension,
            $duration,
            $video_image_name,
            'curriculum',
            $user->instructor,
            $course->getKey()
        );

        return $this->courseVideoRepository->create($courseVideoDto->toArray());
    }

    public function storeAudioForCourse(UploadedFile $audio, Course $course, User $user): CourseFiles
    {
        $file_name = explode('.', $audio->getClientOriginalName());
        $file_name = $file_name[0] . '_' . time() . rand(4, 9999);
        $file_name = str_slug($file_name, "-");

        $file_type = $audio->getClientOriginalExtension();
        $file_title = $audio->getClientOriginalName();
        $file_size = $audio->getSize();
        $file_title = str_slug($file_title, "-");

        $this->videoService->setCredentials($audio->getPathName(), $file_name);

        $duration = $this->videoService->getDuration();

        $audio->storeAs('course/' . $course->getKey(), $file_name . '.' . $file_type);

        $courseFileDto = new CourseFileDto($file_name, $file_title, $file_type, $file_type, $file_size, 'curriculum', $duration, $user, $course, $file_type == 'mp3' ? true : false);

        return $this->courseFileRepository->create($courseFileDto->toArray());
    }

    public function storeDocumentForCourse(UploadedFile $document, Course $course, User $user): CourseFiles
    {
        $pdftext = file_get_contents($document);
        $pdfPages = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        $file_name = explode('.', $document->getClientOriginalName());
        $file_name = $file_name[0] . '_' . time() . rand(4, 9999);
        $file_type = $document->getClientOriginalExtension();

        $document->storeAs('course/' . $course->getKey(), $file_name . '.' . $file_type);

        $courseFileDto = new CourseFileDto(
            $file_name,
            $document->getClientOriginalName(),
            $file_type,
            $file_type,
            $document->getSize(),
            'curriculum',
            $pdfPages,
            $user,
            $course,
            false
        );

        return $this->courseFileRepository->create($courseFileDto->toArray());
    }

    public function storeResourceForCourse(UploadedFile $resource, Course $course, User $user): CourseFiles
    {
        $courseId = $course->getKey();

        $file_name = explode('.', $resource->getClientOriginalName());
        $file_name = $file_name[0] . '_' . time() . rand(4, 9999);
        $file_type = $resource->getClientOriginalExtension();
        $file_title = $resource->getClientOriginalName();
        $file_size = $resource->getSize();

        if ($file_type == 'pdf') {
            $pdftext = file_get_contents($resource);
            $pdfPages = (int)preg_match_all("/\/Page\W/", $pdftext, $dummy);
        } else {
            $pdfPages = 0;
        }

        $resource->storeAs('course/' . $courseId, $file_name . '.' . $file_type);

        $courseFileDto = new CourseFileDto($file_name, $file_title, $file_type, $file_type, $file_size, 'curriculum_resource', $pdfPages, $user, $course, true);

        return $this->courseFileRepository->create($courseFileDto->toArray());
    }

    public function putLectureResource(CurriculumLecturesQuiz $curriculumLecturesQuiz, MediaModelContract $file): MediaModelContract
    {
        $resources = $curriculumLecturesQuiz->resources;

        if (!$resources) {
            $resources = [];
        }

        $resources[] = $file->getKey();

        $this->curriculumLecturesQuizRepository->update(
            ['resources' => array_unique($resources)],
            $curriculumLecturesQuiz->getKey()
        );

        return $file;
    }

    /**
     * @param Course $course
     */
    private function removeCoursePromoVideo(Course $course): void
    {
        if ($course->video) {
            $filename = 'course/' . $course->getKey() . '/' . $course->video->video_title;
            $this->removeExisting($filename . '.' . $course->video->video_type);
            $this->removeExisting($filename . '.jpg');

            $course->video->delete();
        }
    }

    /**
     * @param string|null $fileName
     */
    private function removeExisting(?string $fileName): void
    {
        if (!is_null($fileName) && Storage::exists($fileName)) {
            Storage::delete($fileName);
        }
    }

    /**
     * @param Course $course
     * @return Course
     */
    private function initializeWhenEmpty(Course $course): Course
    {
        if ($course->sections->isEmpty()) {
            $curriculumSectionDto = new CurriculumSectionDto($course, 'Start Here', 1);

            $section = $this->createSection($curriculumSectionDto);

            $curriculumLectureQuizDto = new CurriculumLectureQuizDto(
                $section,
                LectureType::SECTION,
                1,
                'Introduction'
            );

            $this->createLectureQuizRow($curriculumLectureQuizDto);
        }

        return $course->refresh();
    }

    public function removeVideo(CourseVideos $video): void
    {
        Storage::delete($video->path);
        $this->courseVideoRepository->remove($video);
    }

    public function recommended(Authenticatable $user, ?int $skip = null, ?int $limit = null): Collection
    {
        return $this->courseRepository->inCategories($user->interests->pluck('id'), $skip, $limit);
    }

    public function related(Course $course, ?int $skip = null, ?int $limit = null): Collection
    {
        if ($course->related()->count() <= 0) {
            $categories = new Collection($course->category_id);

            if (!is_null($course->category->parent_id)) {
                // push additional top categories
                $categories->push($course->category->parent_id);
                $categories = $categories->merge($course->category->parent->children->pluck('id'));
            }

            return $this->courseRepository
                ->inCategoriesQuery($categories->unique(), $skip, $limit)
                ->where('id', '!=', $course->getKey())
                ->get();
        }

        return $course->related()->inRandomOrder()->get();
    }

    public function relatedMany(array $courses): Collection
    {
        return $this->courseRepository->relatedMany($courses);
    }

    public function popular(?int $skip = null, ?int $limit = null): Collection
    {
        return $this->courseRepository->popular($skip, $limit);
    }


    public function search(CourseSearchDto $courseSearchDto): LengthAwarePaginator
    {
        $criteria = [new CourseSearch($courseSearchDto->getQuery())];

        return $this->getCoursesListByCriteria($criteria);
    }

    public function searchInCategory(CourseSearchDto $courseSearchDto, Category $category): LengthAwarePaginator
    {
        $criteria = [
            new CourseSearch($courseSearchDto->getQuery()),
            new CourseInCategory($category),
        ];

        return $this->getCoursesListByCriteria($criteria);
    }

    private function uploadImage(Model $model, DtoContract $dto, string $type)
    {
        $image = $dto->getImage();
        if ($image) {
            $filename = $image->store($type);
            if ($filename) {
                $model->image_path = $filename;
                $model->save();
            }
        }
    }

    public function removeCourseFile(CourseFiles $file): void
    {
        Storage::delete($file->path);
        $file->delete();
    }

    public function getPopular(PaginationDto $pagination, PeriodDto $period): Collection
    {
        return $this->courseRepository->getByPopularity($pagination, $period->from(), $period->to());
    }

    public function attachOrderedCoursesToUser(Collection $courses, Authenticatable $user): void
    {
        foreach ($courses as $course) {
            if (!$course->buyable instanceof Course) {
                continue;
            }

            CourseProgressCollection::make($user, $course->buyable)->start();
        }
    }
}
