<?php

namespace Tests\APIs;

use App\Enum\ProgressStatus;
use App\Http\Resources\Course\CourseCurriculumResource;
use EscolaLms\Categories\Models\Category;
use App\Models\Course;
use App\Models\User;
use App\ValueObjects\CourseProgressCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\ApiTestTrait;
use Tests\CreatesUsers;
use Tests\MakeServices;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, MakeServices;

    /**
     * @test
     */
    public function test_read_courses(): void
    {
        $this->response = $this->json(
            'GET',
            '/api/courses/'
        );

        $this->response->assertOk();
    }

    public function test_read_course_content(): void
    {
        $course = factory(Course::class)->create();
        $disabledLecture = $course->sections()->first()->lectures()->first();
        $disabledLecture->update(['publish' => 0]);

        $user = $this->makeStudent();
        CourseProgressCollection::make($user, $course)->start();

        $this->response = $this->actingAs($user, 'api')->json(
            'GET',
            '/api/courses/' . $course->getKey() . '/curriculum'
        );

        $this->assertResourceData(new CourseCurriculumResource($this->courseService()->getCourseContent($course)));
        $this->assertIsObject($this->response->getData()->sections[0]->lectures[0]);
        $this->assertNotEquals($this->response->getData()->sections[0]->lectures[0], $disabledLecture->getKey(), 'Course contains unpublished lectures');
        $this->assertNotContains(null, $this->response->getData()->sections[0]->lectures);
        $this->response->assertOk();
    }

    public function test_see_section_and_lecture_images(): void
    {
        $course = factory(Course::class)->create();
        $user = $this->makeStudent();

        CourseProgressCollection::make($user, $course)->start();

        $this->response = $this->actingAs($user, 'api')->json(
            'GET',
            '/api/courses/' . $course->getKey() . '/curriculum'
        );

        $this->response->assertOk();
        $this->assertStringContainsString('storage/section', $this->response->json()['sections'][0]['image']);
        $this->assertStringContainsString('storage/lecture', $this->response->json()['sections'][0]['lectures'][0]['image']);
    }

    /**
     * @test
     */
    public function test_read_recommended_courses(): void
    {
        $category = Category::factory()->create();
        factory(Course::class, 5)->create([
            'category_id' => $category->getKey()
        ]);

        $user = $this->makeStudent();
        $user->interests()->attach($category);

        $this->response = $this->actingAs($user, 'api')->json(
            'GET',
            '/api/courses/recommended'
        );

        $this->response->assertOk();

        foreach (collect($this->response->getData()->data)->pluck('category_id') as $catId) {
            $this->assertContains($catId, $user->interests->pluck('id'));
        }

        $this->assertCount(5, $this->response->getData()->data);
    }

    /**
     * @test
     */
    public function testRelatedCourses(): void
    {
        $parentCategory = Category::factory()->create();
        $category = Category::factory()->create([
            'parent_id' => $parentCategory->getKey()
        ]);

        factory(Course::class)->create([
            'category_id' => $parentCategory->getKey()
        ]);
        $course = factory(Course::class)->create([
            'category_id' => $category->getKey()
        ]);

        $this->response = $this->json(
            'GET',
            '/api/courses/related/' . $course->getKey()
        );

        $this->response->assertOk();
        $this->assertCount(1, $this->response->getData()->data);
    }

    /**
     * @test
     */
    public function testManualRelatedCourses(): void
    {
        $course = factory(Course::class)->create();
        $courseRelated1 = factory(Course::class)->create();
        $courseRelated2 = factory(Course::class)->create();

        $course->related()->sync([
            $courseRelated1->getKey(),
            $courseRelated2->getKey(),
        ]);

        $this->response = $this->json(
            'GET',
            '/api/courses/related/' . $course->getKey()
        );

        $this->response->assertOk();
        $this->response->assertJsonCount(2, 'data');
    }

    public function testManualRelatedManyCourses(): void
    {
        $course = factory(Course::class)->create();
        $courseRelated1 = factory(Course::class)->create();
        $courseRelated2 = factory(Course::class)->create();

        $course2 = factory(Course::class)->create();
        $courseRelated3 = factory(Course::class)->create();

        $course->related()->sync([
            $courseRelated1->getKey(),
            $courseRelated2->getKey(),
        ]);

        $course2->related()->sync([
            $courseRelated1->getKey(),
            $courseRelated3->getKey(),
        ]);

        $this->response = $this->json('GET', '/api/courses/related-many', [
            'courses' => [
                $course->getKey(),
                $course2->getKey()
            ],
        ]);

        $this->response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function testSearchCoursesByCategory(): void
    {
        $category = Category::factory()->create(['parent_id' => null]);
        $category2 = Category::factory()->create(['parent_id' => null]);

        $course = factory(Course::class)->create([
            'category_id' => $category->getKey(),
        ]);

        $course2 = factory(Course::class)->create([
            'category_id' => $category2->getKey(),
        ]);

        $this->response = $this->json(
            'GET',
            '/api/courses/category/' . $category->getKey(),
        );

        $this->response
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonFragment(['id' => $course->getKey()])
            ->assertJsonMissing(['id' => $course2->getKey()]);
    }

    public function testPopularCourses(): void
    {
        DB::table('course_user')->truncate();

        $course1 = factory(Course::class)->create();
        $course2 = factory(Course::class)->create();
        $course3 = factory(Course::class)->create();

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();
        $user4 = factory(User::class)->create();
        $user5 = factory(User::class)->create();

        $user1->courses()->sync([$course1->getKey(), $course3->getKey()]);
        $user2->courses()->sync([$course1->getKey(), $course2->getKey()]);
        $user3->courses()->sync([$course1->getKey(), $course3->getKey()]);
        $user4->courses()->sync([$course1->getKey()]);
        $user5->courses()->sync([$course1->getKey()]);

        $this->response = $this->json('GET', '/api/courses/popular');
        $this->response->assertOk();

        $courses = $this->response->getData()->data;

        $this->assertEquals($course1->getKey(), $courses[0]->id);
        $this->assertEquals($course3->getKey(), $courses[1]->id);
        $this->assertEquals($course2->getKey(), $courses[2]->id);
    }

    /**
     * @test
     */
    public function test_read_course(): Course
    {
        $course = factory(Course::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/courses/' . $course->getKey()
        );

        $data = json_decode($this->response->getContent(), true);

        foreach (['id', 'course_slug', 'instruction_level_id', 'instruction_level', 'course_title', 'keywords', 'video', 'overview', 'duration', 'price', 'strike_out_price', 'active', 'tags', 'shortDesc', 'author', 'lessons', 'related', 'category_id', 'instructor_id', 'is_active', 'created_at', 'updated_at'] as $key) {
            $this->assertArrayHasKey($key, $data);
        }
        $this->response->assertOk();

        return $course;
    }
}
