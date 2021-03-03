<?php

namespace App\Dto;

use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;

class MetricsDto implements DtoContract
{
    private int $courses;
    private int $students;
    private int $instructors;

    private int $coursesWeek;
    private int $studentsWeek;
    private int $instructorsWeek;

    /**
     * MetricsDto constructor.
     * @param int $courses
     * @param int $students
     * @param int $instructors
     * @param int $coursesWeek
     * @param int $studentsWeek
     * @param int $instructorsWeek
     */
    public function __construct(int $courses, int $students, int $instructors, int $coursesWeek, int $studentsWeek, int $instructorsWeek)
    {
        $this->courses = $courses;
        $this->students = $students;
        $this->instructors = $instructors;
        $this->coursesWeek = $coursesWeek;
        $this->studentsWeek = $studentsWeek;
        $this->instructorsWeek = $instructorsWeek;
    }

    public function toArray(): array
    {
        return [
            'courses' => $this->getCourses(),
            'students' => $this->getStudents(),
            'instructors' => $this->getInstructors()
        ];
    }

    public function getCoursesMetric(): array
    {
        return [
            'value' => $this->getCourses(),
            'week_value' => $this->getCoursesWeek(),
            'name' => 'Courses',
            'icon' => 'far fa-play-circle',
            'cardColor' => 'bg-green-600',
            'url' => route('course.list')
        ];
    }

    public function getStudentsMetric(): array
    {
        return [
            'value' => $this->getStudents(),
            'week_value' => $this->getStudentsWeek(),
            'name' => 'Students',
            'icon' => 'far fa-play-circle',
            'cardColor' => 'bg-blue-600',
            'url' => route('admin.users')
        ];
    }

    public function getInstructorsMetric(): array
    {
        return [
            'value' => $this->getInstructors(),
            'week_value' => $this->getInstructorsWeek(),
            'name' => 'Instructors',
            'icon' => 'fas fa-bullhorn',
            'cardColor' => 'bg-red-600',
            'url' => route('admin.users')
        ];
    }

    /**
     * @return int
     */
    public function getCourses(): int
    {
        return $this->courses;
    }

    /**
     * @return int
     */
    public function getStudents(): int
    {
        return $this->students;
    }

    /**
     * @return int
     */
    public function getInstructors(): int
    {
        return $this->instructors;
    }

    /**
     * @return int
     */
    public function getCoursesWeek(): int
    {
        return $this->coursesWeek;
    }

    /**
     * @return int
     */
    public function getStudentsWeek(): int
    {
        return $this->studentsWeek;
    }

    /**
     * @return int
     */
    public function getInstructorsWeek(): int
    {
        return $this->instructorsWeek;
    }
}
