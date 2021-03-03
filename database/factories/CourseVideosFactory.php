<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Course;
use App\Models\CourseVideos;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(CourseVideos::class, function (Faker $faker) {
    return [
        'video_title' => $faker->name,
        'video_name' => $faker->name,
        'video_type' => 'mp4',
        'duration' => '00:02:24',
        'image_name' => 'sample-15568159421897.jpg',
        'video_tag' => $faker->word,
        'uploader_id' => User::first()->getKey(),
        'course_id' => Course::first()->getKey(),
        'processed' => true
    ];
});
