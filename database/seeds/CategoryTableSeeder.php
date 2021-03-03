<?php

namespace Database\Seeders;

use App\Models\Category;
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
            factory(Category::class)->create([
                'name' => 'Development',
                'slug' => 'development',
                'icon_class' => 'fa-chart-line',
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'business',
                'slug' => 'development',
                'icon_class' => 'fa-business-time',
                'is_active' => true,
            ]);

            $category = factory(Category::class)->create([
                'name' => 'IT & Software',
                'slug' => 'IT-software',
                'icon_class' => 'fa-laptop',
                'is_active' => true,
            ]);

            $parentId = $category->id;

            $category = factory(Category::class)->create([
                'name' => 'Frontend',
                'slug' => 'Frontend',
                'icon_class' => 'fa-laptop',
                'parent_id' => $parentId,
                'is_active' => true,
            ]);

            $subparentId = $category->id;

            factory(Category::class)->create([
                'name' => 'React',
                'slug' => 'react',
                'icon_class' => 'fa-laptop',
                'parent_id' => $subparentId,
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Angular',
                'slug' => 'angular',
                'icon_class' => 'fa-laptop',
                'parent_id' => $subparentId,
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Backend',
                'slug' => 'backend',
                'icon_class' => 'fa-laptop',
                'parent_id' => $parentId,
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Artificail Inteligence',
                'slug' => 'ai',
                'icon_class' => 'fa-laptop',
                'parent_id' => $parentId,
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Marketing',
                'slug' => 'marketing',
                'icon_class' => 'fa-funnel-dollar',
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'icon_class' => 'fa-heartbeat',
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Photography',
                'slug' => 'photography',
                'icon_class' => 'fa-camera-retro',
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Health & Fitness',
                'slug' => 'health-fitness',
                'icon_class' => 'fa-medkit',
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Teacher Training',
                'slug' => 'teacher-training',
                'icon_class' => 'fa-chalkboard-teacher',
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Music',
                'slug' => 'music',
                'icon_class' => 'fa-music',
                'is_active' => true,
            ]);

            factory(Category::class)->create([
                'name' => 'Academics',
                'slug' => 'academics',
                'icon_class' => 'fa-user-graduate',
                'is_active' => true,
            ]);
        }

        factory(Category::class, 10)->create();
    }
}
