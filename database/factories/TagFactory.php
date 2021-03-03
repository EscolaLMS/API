<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    $classes = [
        App\Models\Course::class => App\Models\Course::inRandomOrder()->select(['id'])->first()->getKey(),
        App\Models\Blog::class => App\Models\Blog::inRandomOrder()->select(['id'])->first()->getKey()
    ];

    $class = array_rand($classes);

    return [
        'title' => $faker->word,
        'morphable_type' => $class,
        'morphable_id' => $classes[$class],
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
