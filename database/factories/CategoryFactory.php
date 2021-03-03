<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enum\IconsEnum as IconsDictionary;
use App\Models\Category;
use App\Services\EscolaLMS\CategoryService;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->name;
    $icon = Storage::putFile('categories', database_path('seeds/multimedia/categories/') . rand(1, 5) . '.svg', 'public');

    return [
        'name' => $name,
        'slug' => CategoryService::slufigy($name),
        'icon_class' => $faker->randomElement(IconsDictionary::names),
        'icon' => $icon,
        'is_active' => $faker->boolean,
        'parent_id' => rand(0, 1) ? optional(Category::select('id')->inRandomOrder()->first())->getKey() : null,
    ];
});
