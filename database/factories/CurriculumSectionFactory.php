<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Course;
use App\Models\CurriculumSection;
use Faker\Generator as Faker;

$factory->define(CurriculumSection::class, function (Faker $faker) {
    return [
        'course_id' => Course::first()->getKey(),
        'title' => $faker->name,
        'sort_order' => 0,
    ];
});
