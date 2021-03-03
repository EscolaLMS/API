<?php namespace Tests\Repositories;

use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TagRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TagRepository
     */
    protected $tagRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tagRepo = \App::make(TagRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tag()
    {
        $tag = factory(Tag::class)->make()->toArray();

        $createdTag = $this->tagRepo->create($tag);

        $createdTag = $createdTag->toArray();
        $this->assertArrayHasKey('id', $createdTag);
        $this->assertNotNull($createdTag['id'], 'Created Tag must have id specified');
        $this->assertNotNull(Tag::find($createdTag['id']), 'Tag with given id must be in DB');
        $this->assertModelData($tag, $createdTag);
    }

    /**
     * @test read
     */
    public function test_read_tag()
    {
        $tag = factory(Tag::class)->create();

        $dbTag = $this->tagRepo->find($tag->id);

        $dbTag = $dbTag->toArray();
        $this->assertModelData($tag->toArray(), $dbTag);
    }

    /**
     * @test update
     */
    public function test_update_tag()
    {
        $tag = factory(Tag::class)->create();
        $fakeTag = factory(Tag::class)->make()->toArray();

        $updatedTag = $this->tagRepo->update($fakeTag, $tag->id);

        $this->assertModelData($fakeTag, $updatedTag->toArray());
        $dbTag = $this->tagRepo->find($tag->id);
        $this->assertModelData($fakeTag, $dbTag->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tag()
    {
        $tag = factory(Tag::class)->create();

        $resp = $this->tagRepo->delete($tag->id);

        $this->assertTrue($resp);
        $this->assertNull(Tag::find($tag->id), 'Tag should not exist in DB');
    }
}
