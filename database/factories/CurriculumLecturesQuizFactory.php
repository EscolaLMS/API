<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enum\LectureType;
use App\Models\CurriculumLecturesQuiz;
use App\Models\CurriculumSection;
use Faker\Generator as Faker;

$factory->define(CurriculumLecturesQuiz::class, function (Faker $faker) {
    return [
        'section_id' => CurriculumSection::first()->getKey(),
        'type' => LectureType::SECTION,
        'title' => $faker->title,
        'description' => $faker->paragraphs(3, true),
        'contenttext' => $faker->paragraphs(3, true),
        'media' => null,
        'media_type' => null,
        'sort_order' => 1,
        'publish' => true,
        'resources' => [],
    ];
});
