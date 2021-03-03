<?php

namespace Database\Seeders;

use App\Enum\LectureType;
use App\Enum\MediaType;
use App\Models\Course;
use App\Models\H5PContent;
use Illuminate\Database\Seeder;

class H5PContentCoursesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::all();
        $h5ps = H5PContent::all();
        $so = 0;

        foreach ($courses as $course) {
            $count = count($course->sections()->get());
            $course->loadCount('sections');
            $section = $course->sections()->create([
                'title' => 'Interactive Elements',
                'sort_order' => $course->sections_count
            ]);

            foreach ($h5ps as $h5p) {
                $course->insertLectureQuizRow([
                    'section_id' => $section->section_id,
                    'type' => LectureType::SECTION ,
                    'sort_order' => $so++,
                    'title' => 'Example of '. $h5p->title,
                    'description' => 'Description of '. $h5p->title,
                    // 'contenttext' ?? null,
                    'media' => $h5p->id,
                    'media_type' => MediaType::INTERACTIVE,
                    // 'resources' ?? null,
                    'publish' => 1
                ]);
            }
        }
    }
}
