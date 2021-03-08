<?php

namespace App\Dto;

use App\Models\Course;
use App\Models\CurriculumSection;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class CurriculumSectionDto implements DtoContract, InstantiateFromRequest
{
    private Course $course;
    private string $title;
    private int $sortOrder;
    private ?UploadedFile $image;
    private ?CurriculumSection $section;

    /**
     * CurriculumSectionDto constructor.
     * @param Course $course
     * @param string $title
     * @param int $sortOrder
     * @param CurriculumSection|null $curriculumSection
     * @param UploadedFile|null $image
     */
    public function __construct(Course $course, string $title, int $sortOrder, ?CurriculumSection $curriculumSection = null, ?UploadedFile $image = null)
    {
        $this->course = $course;
        $this->title = $title;
        $this->sortOrder = $sortOrder;
        $this->section = $curriculumSection;
        $this->image = $image;
    }

    public function toArray(): array
    {
        return [
            'course_id' => $this->getCourse()->getKey(),
            'title' => $this->getTitle(),
            'sort_order' => $this->getSortOrder()
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            Course::find($request->input('course_id')),
            $request->input('section'),
            $request->input('position'),
            $request->route('curriculumSection'),
            $request->file('image')
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * @return CurriculumSection|null
     */
    public function getSection(): ?CurriculumSection
    {
        return $this->section;
    }
    /**
     * @return UploadedFile|null
     */
    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function getSectionId(): ?int
    {
        $section = $this->getSection();
        return $section ? $section->getKey() : null;
    }
}
