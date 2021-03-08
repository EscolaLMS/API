<?php

namespace App\Dto;

use App\Models\Instructor;
use EscolaLms\Core\Dtos\Contracts\DtoContract;

class CourseVideoDto implements DtoContract
{
    private string $title;
    private string $name;
    private string $type;
    private string $duration;
    private string $image_name;
    private string $video_tag;
    private Instructor $instructor;
    private int $course_id;
    private bool $processed;

    /**
     * CourseVideoDto constructor.
     * @param string $title
     * @param string $name
     * @param string $type
     * @param string $duration
     * @param string $image_name
     * @param string $video_tag
     * @param Instructor $instructor
     * @param int $course_id
     * @param bool $processed
     */
    public function __construct(string $title, string $name, string $type, string $duration, string $image_name, string $video_tag, Instructor $instructor, int $course_id, bool $processed = true)
    {
        $this->title = $title;
        $this->name = $name;
        $this->type = $type;
        $this->duration = $duration;
        $this->image_name = $image_name;
        $this->video_tag = $video_tag;
        $this->instructor = $instructor;
        $this->course_id = $course_id;
        $this->processed = $processed;
    }

    public function toArray(): array
    {
        return [
            'video_title' => $this->getTitle(),
            'video_name' => $this->getName(),
            'video_type' => $this->getType(),
            'duration' => $this->getDuration(),
            'image_name' => $this->getImageName(),
            'video_tag' => $this->getVideoTag(),
            'uploader_id' => $this->getInstructor()->user->getKey(),
            'course_id' => $this->getCourseId(),
            'processed' => $this->isProcessed()
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getImageName(): string
    {
        return $this->image_name;
    }

    /**
     * @return string
     */
    public function getVideoTag(): string
    {
        return $this->video_tag;
    }

    /**
     * @return Instructor
     */
    public function getInstructor(): Instructor
    {
        return $this->instructor;
    }

    /**
     * @return int
     */
    public function getCourseId(): int
    {
        return $this->course_id;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }
}
