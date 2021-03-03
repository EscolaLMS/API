<?php

namespace Tests\Unit\Models;

use App\Models\Course;
use App\Models\CourseFiles;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CourseFilesTest extends TestCase
{
    use WithFaker;

    public function testUrlAttribute()
    {
        Storage::fake();

        $title = $this->faker->word;
        $course = factory(Course::class)->make([
            'id' => 1
        ]);

        $courseVideo = new CourseFiles([
            'id' => 1,
            'course' => $course,
            'file_name' => $title,
            'file_type' => 'mp3'
        ]);
        $this->assertEquals(route('file', ['path' => $courseVideo->path]), $courseVideo->url);
    }
}
