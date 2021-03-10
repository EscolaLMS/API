<?php

namespace Database\Seeders;

use EscolaLms\Categories\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $is_exist = Category::all();

        if (!$is_exist->count()) {
            Category::factory()->create([
                'name' => 'Development',
                'slug' => 'development',
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'business',
                'slug' => 'development',
                'is_active' => true,
            ]);

            $category = Category::factory()->create([
                'name' => 'IT & Software',
                'slug' => 'IT-software',
                'is_active' => true,
            ]);

            $parentId = $category->id;

            $category = Category::factory()->create([
                'name' => 'Frontend',
                'slug' => 'Frontend',
                'parent_id' => $parentId,
                'is_active' => true,
            ]);

            $subparentId = $category->id;

            Category::factory()->create([
                'name' => 'React',
                'slug' => 'react',
                'parent_id' => $subparentId,
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Angular',
                'slug' => 'angular',
                'parent_id' => $subparentId,
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Backend',
                'slug' => 'backend',
                'parent_id' => $parentId,
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Artificail Inteligence',
                'slug' => 'ai',
                'parent_id' => $parentId,
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Marketing',
                'slug' => 'marketing',
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Photography',
                'slug' => 'photography',
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Health & Fitness',
                'slug' => 'health-fitness',
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Teacher Training',
                'slug' => 'teacher-training',
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Music',
                'slug' => 'music',
                'is_active' => true,
            ]);

            Category::factory()->create([
                'name' => 'Academics',
                'slug' => 'academics',
                'is_active' => true,
            ]);
        }

        Category::factory()->count(10)->create();
    }
}
