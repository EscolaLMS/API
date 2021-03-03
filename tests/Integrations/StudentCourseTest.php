<?php

namespace Tests\Integrations;

use App\Enum\UserRole;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Tests\MakeServices;
use Tests\TestCase;

class StudentCourseTest extends TestCase
{
    use DatabaseTransactions, MakeServices;

    public function testStudentCourseList(): void
    {
        // TODO: check get student courses
        $course = factory(Course::class)->create();
        $user = factory(User::class)->create();
        $user->assignRole(UserRole::STUDENT);

        $courses = $this->courseService()->getStudentCourses($user);

        $this->assertInstanceOf(Collection::class, $courses);
    }
}
