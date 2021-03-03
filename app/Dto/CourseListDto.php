<?php


namespace App\Dto;

use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CourseListDto implements DtoContract
{
    private LengthAwarePaginator $courses;
    private Collection $categories;
    private Collection $instruction_levels;

    public function __construct(LengthAwarePaginator $courses, Collection $categories, Collection $instruction_levels)
    {
        $this->courses = $courses;
        $this->categories = $categories;
        $this->instruction_levels = $instruction_levels;
    }

    public function toArray(): array
    {
        return [
            'courses' => $this->getCourses(),
            'categories' => $this->getCategories(),
            'instruction_levels' => $this->getInstructionLevels()
        ];
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getCourses(): LengthAwarePaginator
    {
        return $this->courses;
    }

    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @return Collection
     */
    public function getInstructionLevels(): Collection
    {
        return $this->instruction_levels;
    }
}
