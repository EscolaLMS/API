<?php

namespace Features\Traits;

use App\Models\Category;
use App\Models\Course;
use App\Services\EscolaLMS\CategoryService;

trait CreateCourses
{
    public function createCategory(string $name): Category
    {
        // TODO this is not how slugs are made
        return Category::firstOrCreate(['name' => $name, 'slug' => CategoryService::slufigy($name)]);
    }

    public function findCourseByName(string $name): Course
    {
        $course = Course::where('course_title', $name)->first();
        if ($course) {
            return $course;
        }
        $course = factory(Course::class)->create(['course_title' => $name]);

        return $course;
        return Course::firstOrCreate(['course_title' => $name]);
    }
}
