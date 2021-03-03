<?php

namespace App\Services\EscolaLMS\Media;

use App\Enum\MediaType;
use App\Models\Contracts\MediaModelContract;
use App\Models\CurriculumLecturesQuiz;
use App\Repositories\Contracts\CourseFileRepositoryContract;
use App\Repositories\Contracts\CourseVideoRepositoryContract;
use App\Repositories\Contracts\H5PContentRepositoryContract;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use App\Services\EscolaLMS\Contracts\MediaContract;
use Illuminate\Support\Facades\Storage;

abstract class Media implements MediaContract
{
    protected CourseServiceContract $courseService;
    protected CourseFileRepositoryContract $courseFileRepository;
    protected CourseVideoRepositoryContract $courseVideoRepository;
    protected H5PContentRepositoryContract $h5pContentRepository;

    // Working object
    protected CurriculumLecturesQuiz $lecture;
    protected ?MediaModelContract $medium = null;

    /**
     * Media constructor.
     * @param CourseServiceContract $courseService
     * @param CourseFileRepositoryContract $courseFileRepository
     * @param CourseVideoRepositoryContract $courseVideoRepository
     */
    public function __construct(
        CourseServiceContract $courseService,
        CourseFileRepositoryContract $courseFileRepository,
        CourseVideoRepositoryContract $courseVideoRepository,
        H5PContentRepositoryContract $h5pContentRepository
    )
    {
        $this->courseService = $courseService;
        $this->courseFileRepository = $courseFileRepository;
        $this->courseVideoRepository = $courseVideoRepository;
        $this->h5pContentRepository = $h5pContentRepository;
    }

    public static function make(CurriculumLecturesQuiz $lecture): MediaContract
    {
        $className = self::getClassName($lecture);

        if (!class_exists($className)) {
            throw new \RuntimeException("Media class `{$className}` dosn't exists.");
        }

        $mediaInstance = app($className);
        $mediaInstance->setCurriculumLecturesQuiz($lecture);
        return $mediaInstance;
    }

    public function get()
    {
        if (!is_null($this->medium)) {
            return $this->medium;
        }

        return $this->medium = $this->courseFileRepository->find($this->lecture->media);
    }

    public function getResource(): array
    {
        $this->medium = $this->get();

//        TODO: Remove if required
//        if (is_null($this->medium)) {
//            return [];
//        }

        $data = $this->medium->toArray();
        $data['url'] = $this->getUrl();
        $data['type'] = MediaType::getName($this->lecture->media_type);

        unset($data['course']);


        return $data;
    }

    public function getUrl(): string
    {
        $path = 'course/' . $this->medium->course_id . '/' . $this->medium->file_name . '.' . $this->medium->file_extension;

        return route('file', ['path' => $path]);
    }

    public function getLecture(): CurriculumLecturesQuiz
    {
        return $this->lecture;
    }

    public function delete(bool $withoutTrashing = false): void
    {
        if (is_null($this->lecture->media)) {
            return;
        }

        $file = $this->get();

        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        if (!$withoutTrashing) {
            $file->delete();
        }
    }

    protected static function getClassName(CurriculumLecturesQuiz $lecture): string
    {
        $typeName = ucfirst(camel_case(strtolower(MediaType::getName(intval($lecture->media_type)))));

        if (!is_numeric($lecture->media_type) || empty($typeName)) {
            return NullableMedia::class;
        }

        return 'App\\Services\\EscolaLMS\\Media\\' . $typeName . 'Media';
    }

    protected function setCurriculumLecturesQuiz(CurriculumLecturesQuiz $lecture): void
    {
        $this->lecture = $lecture;
    }
}
