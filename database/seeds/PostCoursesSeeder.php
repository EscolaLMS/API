<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Courses\Models\Topic;
use EscolaLms\HeadlessH5P\Models\H5PContent;
use EscolaLms\TopicTypes\Models\TopicContent\H5P;
use Peopleaps\Scorm\Model\ScormScoModel;

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
        $scormScos = ScormScoModel::all();
        
        if ( $contents->count() == 0) {
            return; 
        }
        

        foreach ($courses as $course) {
            $rnd = rand(1,2);
            switch($rnd) {
                case 1: // scorm
                    $course->scorm_sco_id = $scormScos->random()->id;
                    $course->save();
                    break;
                case 2: // sylabus
                default:
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
}
