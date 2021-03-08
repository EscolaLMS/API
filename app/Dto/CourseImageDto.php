<?php

namespace App\Dto;

use App\Models\Course;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class CourseImageDto implements DtoContract, InstantiateFromRequest
{
    private Course $course;
    private ?string $old_course_image;
    private ?string $old_thumb_image;
    private ?string $course_image_base64;
    private UploadedFile $course_image;

    /**
     * CourseImageDto constructor.
     * @param Course $course
     * @param string|null $old_course_image
     * @param string|null $old_thumb_image
     * @param string|null $course_image_base64
     * @param UploadedFile $course_image
     */
    public function __construct(Course $course, ?string $old_course_image, ?string $old_thumb_image, ?string $course_image_base64, UploadedFile $course_image)
    {
        $this->course = $course;
        $this->old_course_image = $old_course_image;
        $this->old_thumb_image = $old_thumb_image;
        $this->course_image_base64 = $course_image_base64;
        $this->course_image = $course_image;
    }

    public function toArray(): array
    {
        return [
            'old_course_image' => $this->getOldCourseImage(),
            'old_thumb_image' => $this->getOldThumbImage(),
            'course_image_base64' => $this->getCourseImageBase64(),
            'course_image' => $this->getCourseImage(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->route('course'),
            $request->get('old_course_image'),
            $request->get('old_thumb_image'),
            $request->get('course_image_base64'),
            $request->file('course_image')
        );
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return string|null
     */
    public function getOldCourseImage(): ?string
    {
        return $this->old_course_image;
    }

    /**
     * @return string|null
     */
    public function getOldThumbImage(): ?string
    {
        return $this->old_thumb_image;
    }

    /**
     * @return string|null
     */
    public function getCourseImageBase64(): ?string
    {
        return $this->course_image_base64;
    }

    /**
     * @return UploadedFile
     */
    public function getCourseImage(): UploadedFile
    {
        return $this->course_image;
    }
}
