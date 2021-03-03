<?php

namespace Tests\Unit\Models;

use App\Models\Course;
use App\Models\CourseVideos;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CourseVideoTest extends TestCase
{
    use WithFaker;

    public function test_UrlAttribute()
    {
        Storage::fake();

        $title = $this->faker->word;
        $course = factory(Course::class)->make([
            'id' => 1
        ]);

        $courseVideo = new CourseVideos([
            'course' => $course,
            'video_title' => $title,
            'video_type' => 'mp4'
        ]);
        $this->assertEquals(Storage::url('course/' . $course->getKey() . '/' . $title . '.mp4'), $courseVideo->url);
    }
}
