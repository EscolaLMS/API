<?php

namespace App\Dto;

use App\Models\Course;
use App\Models\User;
use EscolaLms\Core\Dtos\Contracts\DtoContract;

class CourseFileDto implements DtoContract
{
    private string $fileName;
    private string $fileTitle;
    private string $fileType;
    private string $fileExtension;
    private string $fileSize;
    private string $fileTag;
    private string $duration;
    private User $uploader;
    private Course $course;
    private bool $processed;

    /**
     * CourseFileDto constructor.
     * @param string $fileName
     * @param string $fileTitle
     * @param string $fileType
     * @param string $fileExtension
     * @param string $fileSize
     * @param string $fileTag
     * @param string $duration
     * @param User $uploader
     * @param Course $course
     * @param bool $processed
     */
    public function __construct(string $fileName, string $fileTitle, string $fileType, string $fileExtension, string $fileSize, string $fileTag, string $duration, User $uploader, Course $course, bool $processed)
    {
        $this->fileName = $fileName;
        $this->fileTitle = $fileTitle;
        $this->fileType = $fileType;
        $this->fileExtension = $fileExtension;
        $this->fileSize = $fileSize;
        $this->fileTag = $fileTag;
        $this->duration = $duration;
        $this->uploader = $uploader;
        $this->course = $course;
        $this->processed = $processed;
    }

    public function toArray(): array
    {
        return [
            'file_name' => $this->getFileName(),
            'file_title' => $this->getFileTitle(),
            'file_type' => $this->getFileType(),
            'file_extension' => $this->getFileExtension(),
            'file_size' => $this->getFileSize(),
            'duration' => $this->getDuration(),
            'file_tag' => $this->getFileTag(),
            'uploader_id' => $this->getUploader()->getKey(),
            'course_id' => $this->getCourse()->getKey(),
            'processed' => $this->isProcessed(),
        ];
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getFileTitle(): string
    {
        return $this->fileTitle;
    }

    /**
     * @return string
     */
    public function getFileType(): string
    {
        return $this->fileType;
    }

    /**
     * @return string
     */
    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    /**
     * @return string
     */
    public function getFileSize(): string
    {
        return $this->fileSize;
    }

    /**
     * @return string
     */
    public function getFileTag(): string
    {
        return $this->fileTag;
    }

    /**
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * @return User
     */
    public function getUploader(): User
    {
        return $this->uploader;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }
}
