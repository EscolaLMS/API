<?php

namespace App\ValueObjects;

use App\Models\Course;
use App\Models\CurriculumLecturesQuiz;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use App\Services\EscolaLMS\Media\Media;
use App\ValueObjects\Contracts\ValueObjectContract;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use Illuminate\Support\Collection;

class CourseContent extends ValueObject implements DtoContract, ValueObjectContract
{
    private CourseServiceContract $courseService;

    private Course $course;
    private array $lecturesQuizzes;
    private array $lecturesMedia;
    private array $lecturesResources;
    private array $lecturesQuizQuestions;

    public function __construct(CourseServiceContract $courseService)
    {
        $this->courseService = $courseService;
    }

    public function build(Course $course): self
    {
        $this->course = $course;
        $this->lecturesQuizzes = [];
        $this->lecturesMedia = [];
        $this->lecturesResources = [];
        $this->lecturesQuizQuestions = [];

        foreach ($this->getSections() as $section) {
            $this->lecturesQuizzes[$section->getKey()] = $this->courseService->getLectureQuizzes($section);

            foreach ($this->lecturesQuizzes[$section->getKey()] as $lecture) {
                if (!($lecture instanceof CurriculumLecturesQuiz)) {
                    continue;
                }

                if ($lecture->hasMedia) {
                    $medium = Media::make($lecture);
                    $this->lecturesMedia[$section->section_id][$lecture->lecture_quiz_id] = $medium->get();
                }

                if (!is_null($lecture->resources)) {
                    foreach ($lecture->resources as $fileId) {
                        $this->lecturesResources[$section->section_id][$lecture->lecture_quiz_id][] = $this->courseService->findCourseFile($fileId);
                    }
                }
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'course' => $this->getCourse(),
            'sections' => $this->getSections(),
            'lecturesquiz' => $this->getLecturesQuizzes(),
            'lecturesmedia' => $this->getLecturesMedia(),
            'lecturesresources' => $this->getLecturesResources(),
            'lecturesquizquestions' => $this->getLecturesQuizQuestions()
        ];
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return Collection
     */
    public function getSections(): Collection
    {
        return $this->course->sections;
    }

    public function getLectures(): Collection
    {
        $lectures = new Collection();

        foreach ($this->lecturesQuizzes as $resource) {
            $lectures = $lectures->merge($resource);
        }

        return $lectures;
    }

    /**
     * @return array
     */
    public function getLecturesQuizzes(): array
    {
        return $this->lecturesQuizzes;
    }

    /**
     * @return array
     */
    public function getLecturesMedia(): array
    {
        return $this->lecturesMedia;
    }

    /**
     * @return array
     */
    public function getLecturesResources(): array
    {
        return $this->lecturesResources;
    }

    /**
     * @return array
     */
    public function getLecturesQuizQuestions(): array
    {
        return $this->lecturesQuizQuestions;
    }

    public function getRelated(): Collection
    {
        return $this->courseService->related($this->course);
    }


}
