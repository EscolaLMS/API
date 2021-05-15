<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Courses\Models\Lesson;
use EscolaLms\Courses\Models\Topic;
use EscolaLms\HeadlessH5P\Models\H5PContent;
use EscolaLms\Courses\Models\TopicContent\H5P;

class PostCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::with('lessons')->get();
        $contents = H5PContent::with('library')->get();

        foreach ($courses as $course) {
            foreach ($course->lessons as $lesson) {
                $content = $contents->random();
                $topic = Topic::create([
                    'lesson_id'=>$lesson->id,
                    'title'=> $content->library->title,
                    'order' => 0
                ]);
                $topicable = H5P::create([
                    'value' => $content->id
                ]);
                $topic->topicable()->associate($topicable)->save();
            }
        }
    }
}
