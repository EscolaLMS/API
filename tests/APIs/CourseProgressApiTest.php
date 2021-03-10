<?php

namespace Tests\APIs;

use EscolaLms\Categories\Models\Category;
use App\Models\Course;
use App\Models\User;
use EscolaLms\Core\Repositories\Contracts\ConfigRepositoryContract;
use App\ValueObjects\CourseProgressCollection;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\ApiTestTrait;
use Tests\CreatesUsers;
use Tests\MakeServices;
use Tests\ProgressConfigurable;
use Tests\TestCase;

class CourseProgressApiTest extends TestCase
{
    use ApiTestTrait, CreatesUsers, WithFaker, ProgressConfigurable, MakeServices;

    /**
     * @test
     */
    public function test_update_course_progress(): array
    {
        Mail::fake();
        Notification::fake();
        Queue::fake();
        $configRepository = app(ConfigRepositoryContract::class);
        $course = factory(Course::class)->create();

        $student = $this->makeStudent([
        ]);

        $this->response = $this->actingAs($student, 'api')->json(
            'PATCH',
            '/api/courses/progress/' . $course->getKey(),
            ['progress' => $this->getProgressUpdate($course)]
        );

        $courseProgress = CourseProgressCollection::make($student, $course);

        $this->response->assertOk();
        $this->assertTrue($courseProgress->isFinished());
        $this->assertResourceData($courseProgress->getProgress());

        return [$course, $student, $courseProgress];
    }

    /**
     * @test
     * @depends test_update_course_progress
     */
    public function test_read_course_progress(array $payload): void
    {
        [$course, $student, $courseProgress] = $payload;

        $this->response = $this->actingAs($student, 'api')->json(
            'GET',
            '/api/courses/progress/' . $course->getKey()
        );

        $this->assertResourceData($courseProgress->getProgress());
    }

    /**
     * @test
     * @depends test_update_course_progress
     */
    public function getAllProgresses(array $payload): void
    {
        [$course, $student, $courseProgress] = $payload;

        $student->courses()->attach($course);

        $this->response = $this->actingAs($student, 'api')->json(
            'GET',
            '/api/courses/progress'
        );

        $this->response->assertOk();
        $this->assertCount(1, $this->response->getData());
        $this->assertEquals($this->response->getData()[0]->course->id, $course->getKey());
        $this->assertEquals(json_encode($courseProgress->getProgress()), json_encode($this->response->getData()[0]->progress));
    }

    /**
     * @test
     */
    public function test_read_course_progress_after_updating_courses_three_times(): void
    {
        $course = factory(Course::class)->create();
        $student = $this->makeStudent();

        $this->progressUpdate($course, $student);
        $this->progressUpdate($course, $student);
        $courseProgress = $this->progressUpdate($course, $student);

        $this->response = $this->actingAs($student, 'api')->json(
            'GET',
            '/api/courses/progress/' . $course->getKey()
        );

        $this->assertResourceData($courseProgress->getProgress());
        $this->assertCount($course->lectures->count('lecture_quiz_id'), $courseProgress->getProgress()->pluck('lecture_id'));
        $this->assertInstanceOf(Carbon::class, CourseProgressCollection::make($student, $course)->getFinishDate());
    }

    public function test_update_time_progress(): void
    {
        $pingDuration = 1;

        $course = factory(Course::class)->create();
        $lecture = $course->lectures->first();
        $user = $this->makeStudent();

        $this->pingRequest($user, $lecture);
        sleep($pingDuration);
        $this->pingRequest($user, $lecture);

        $this->assertEquals($pingDuration, $this->courseProgressRepository()->findProgress($lecture, $user)->seconds);
        $this->assertEquals($pingDuration, CourseProgressCollection::make($user, $course)->getTotalSpentTime());
    }


    /**
     * @param $user
     * @param $lecture
     */
    private function pingRequest(User $user, $lecture): void
    {
        $this->response = $this->actingAs($user, 'api')->json(
            'PUT',
            '/api/courses/progress/' . $lecture->getKey() . '/ping'
        );
    }
}
