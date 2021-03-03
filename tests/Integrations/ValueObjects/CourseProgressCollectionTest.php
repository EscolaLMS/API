<?php

namespace Tests\Integrations\ValueObjects;

use App\Enum\ProgressStatus;
use App\Models\Course;
use App\ValueObjects\CourseProgressCollection;
use Tests\CreatesUsers;
use Tests\TestCase;

class CourseProgressCollectionTest extends TestCase
{
    use CreatesUsers;

    public function test_progress_status(): void
    {
        $course = factory(Course::class)->create();
        $user = $this->makeAdmin();

        $progress = CourseProgressCollection::make($user, $course);

        $progress->setProgress([
            [
                'lecture_id' => $course->lectures->first()->getKey(),
                'status' => ProgressStatus::COMPLETE
            ],
            [
                'lecture_id' => $course->lectures->last()->getKey(),
                'status' => ProgressStatus::IN_PROGRESS
            ]
        ]);

        $progressResult = $progress->getProgress();

        $this->assertEquals($progressResult->firstWhere('lecture_id', $course->lectures->first()->getKey())['status'], ProgressStatus::COMPLETE);
        $this->assertEquals($progressResult->firstWhere('lecture_id', $course->lectures->last()->getKey())['status'], ProgressStatus::IN_PROGRESS);
    }

    public function test_progress_when_not_full(): array
    {
        $course = factory(Course::class)->create();
        $user = $this->makeAdmin();

        $progress = CourseProgressCollection::make($user, $course);

        $this->assertFalse($progress->isFinished());

        return [$course, $progress];
    }

    /**
     * @depends test_progress_when_not_full
     */
    public function test_progress_when_full(array $payload): void
    {
        [$course, $progress] = $payload;

        $data = [];
        foreach ($course->lectures->pluck('lecture_quiz_id') as $lectureId) {
            $data[] = [
                'lecture_id' => $lectureId,
                'status' => ProgressStatus::COMPLETE
            ];
        }

        $progress->setProgress($data);

        $this->assertTrue($progress->isFinished());
    }
}
