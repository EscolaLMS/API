<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Course;
use App\Models\CourseFiles;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(CourseFiles::class, function (Faker $faker) {
    $types = [
        'mp3' => ['music'],
        'wav' => ['music'],
        'pdf' => ['curriculum_resource', 'curriculum', 'curriculum_presentation'],
        'doc' => ['curriculum_resource'],
        'docx' => ['curriculum_resource'],
        'link' => ['link']
    ];

    $fileType = $faker->randomElement(array_keys($types));

    return [
        'file_name' => $faker->name,
        'file_title' => $faker->name,
        'file_type' => $fileType,
        'file_extension' => $fileType,
        'file_size' => $faker->numberBetween(100, 100000),
        'duration' => null,
        'file_tag' => $faker->randomElement($types[$fileType]),
        'uploader_id' => User::first()->getKey(),
        'course_id' => Course::first()->getKey(),
        'processed' => true
    ];
});
