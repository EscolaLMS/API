<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CourseProgress;
use Faker\Generator as Faker;

$factory->define(CourseProgress::class, function (Faker $faker) {
    return [
        'course_id' => $faker->word,
        'user_id' => $faker->word,
        'status' => $faker->word,
        'finished_at' => $faker->date('Y-m-d H:i:s'),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
