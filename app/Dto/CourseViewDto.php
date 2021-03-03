<?php


namespace App\Dto;

use App\Models\Course;
use App\Models\CourseVideos;
use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;

class CourseViewDto implements DtoContract
{
    private Course $course;
    private $curriculum_sections;
    private $lectures_count;
    private $videos_count;
    private bool $is_curriculum;
    private CourseVideos $video;

    public function __construct(Course $course, array $curriculum)
    {
        $this->course = $course;
        $this->curriculum_sections = $curriculum['sections'];
        $this->lectures_count = $curriculum['lectures_count'];
        $this->videos_count = $curriculum['videos_count'];
        $this->is_curriculum = (bool) $curriculum['is_curriculum'];
        $this->video = $course->video;
    }

    public function toArray(): array
    {
        return [
            'course' => $this->getCourse(),
            'curriculum_sections' => $this->getCurriculumSections(),
            'lectures_count' => $this->getLecturesCount(),
            'videos_count' => $this->getVideosCount(),
            'video' => $this->getVideo(),
            'is_curriculum' => $this->isCurriculum()
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
     * @return mixed
     */
    public function getCurriculumSections()
    {
        return $this->curriculum_sections;
    }

    /**
     * @return mixed
     */
    public function getLecturesCount()
    {
        return $this->lectures_count;
    }

    /**
     * @return mixed
     */
    public function getVideosCount()
    {
        return $this->videos_count;
    }

    /**
     * @return boolean
     */
    public function isCurriculum(): bool
    {
        return $this->is_curriculum;
    }

    /**
     * @return CourseVideos
     */
    public function getVideo(): CourseVideos
    {
        return $this->video;
    }
}
