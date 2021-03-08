<?php

namespace App\Dto;

use App\Enum\UserRole;
use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use Illuminate\Http\Request;

abstract class CourseDto implements DtoContract
{
    protected ?Course $course;
    protected string $title;
    protected ?Instructor $instructor;
    protected Category $category;
    protected int $instructionLevel;
    protected ?string $keywords;
    protected ?string $overview;
    protected ?string $duration;
    protected ?string $price;
    protected ?string $strikeOutPrice;
    protected bool $isActive;
    protected ?string $tags;
    protected ?string $summary;
    protected int $instructorIncome;

    /**
     * CourseDto constructor.
     * @param Course|null $course
     * @param string $title
     * @param Instructor|null $instructor
     * @param Category $category
     * @param int $instructionLevel
     * @param string|null $keywords
     * @param string|null $overview
     * @param string|null $duration
     * @param string|null $price
     * @param string|null $strikeOutPrice
     * @param bool $isActive
     * @param string|null $tags
     * @param string|null $summary
     */
    public function __construct(?Course $course, string $title, ?Instructor $instructor, Category $category, int $instructionLevel, ?string $keywords, ?string $overview, ?string $duration, ?string $price, ?string $strikeOutPrice, bool $isActive = false, ?string $tags, ?string $summary = null, int $instructorIncome = 0)
    {
        $this->course = $course;
        $this->title = $title;
        $this->instructor = $instructor;
        $this->category = $category;
        $this->instructionLevel = $instructionLevel;
        $this->keywords = $keywords;
        $this->overview = $overview;
        $this->duration = $duration;
        $this->price = $price;
        $this->strikeOutPrice = $strikeOutPrice;
        $this->isActive = $isActive;
        $this->tags = $tags;
        $this->summary = $summary;
        $this->instructorIncome = $instructorIncome;
    }

    public function toArray(): array
    {
        return [
            'course_slug' => $this->getSlug(),
            'course_title' => $this->getTitle(),
            'instructor_id' => $this->getInstructor() ? $this->getInstructor()->getKey() : null,
            'category_id' => $this->getCategory()->getKey(),
            'instruction_level_id' => $this->getInstructionLevel(),
            'keywords' => $this->getKeywords(),
            'overview' => $this->getOverview(),
            'duration' => $this->getDuration(),
            'price' => $this->getPrice(),
            'strike_out_price' => $this->getStrikeOutPrice(),
            'is_active' => $this->isActive(),
            'tags' => $this->getTags(),
            'summary' => $this->getSummary(),
            'instructor_income' => $this->getInstructorIncome()
        ];
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->course->course_slug ?? '';
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Instructor|null
     */
    public function getInstructor(): ?Instructor
    {
        return $this->instructor;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }


    /**
     * @return int
     */
    public function getInstructionLevel(): int
    {
        return $this->instructionLevel;
    }

    /**
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @return string|null
     */
    public function getOverview(): ?string
    {
        return $this->overview;
    }

    /**
     * @return string|null
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getStrikeOutPrice(): ?string
    {
        return $this->strikeOutPrice;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        if (is_null($this->tags)) {
            return [];
        }
        return array_map(
            fn ($name) => ['title' => strtolower(trim($name))],
            explode(',', $this->tags)
        );
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getInstructorIncome(): int
    {
        return $this->instructorIncome;
    }

    public static function getInstructorFromRequest(Request $request): ?Instructor
    {
        return ($request->user()->hasRole(UserRole::ADMIN) ? Instructor::find($request->input('instructor_id')) : null) ?? $request->user()->instructor;
    }

    public static function getInstructorIncomeFromRequest(Request $request): ?int
    {
        return $request->user()->hasRole(UserRole::ADMIN) ? $request->input('instructor_income') : null;
    }
}
