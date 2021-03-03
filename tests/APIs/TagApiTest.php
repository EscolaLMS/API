<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Tag;
use App\Models\Course;

class TagApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;


    /**
     * @test
     */
    public function test_index_tags()
    {
        $tag = factory(Tag::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/tags'
        );

        $this->assertApiSuccess();

        $response = json_decode($this->response->getContent(), true);
        $responseData = $response['data'];


        $searchTags = array_filter($responseData, function ($stag) use ($tag) {
            return $stag['title'] === $tag->title ;
        });

        $this->assertTrue(count($searchTags) > 0);
    }

    /**
     * @test
     */
    public function test_unique_tags()
    {
        $tag_name = "placeat";

        $course = Course::take(1)->first();

        $course->tags()->createMany([
          ["title"=>$tag_name],
          ["title"=>$tag_name],
          ["title"=>$tag_name],
          ["title"=>$tag_name],
          ["title"=>$tag_name],
          ["title"=>$tag_name]
        ]);

        $this->response = $this->json(
            'GET',
            '/api/tags/unique'
        );

        $this->assertApiSuccess();

        $response = json_decode($this->response->getContent(), true);
        $responseData = $response['data'];

        $searchTags = array_filter($responseData, function ($stag) use ($tag_name) {
            return $stag === $tag_name ;
        });

        $this->assertTrue(count($searchTags) === 1);
    }
}
