<?php

namespace EscolaLms\Core\Tests\Features;

use App\Models\User;
use EscolaLms\Core\Models\Attachment;
use EscolaLms\Core\Repositories\AttachmentRepository;
use EscolaLms\Core\Repositories\Contracts\AttachmentRepositoryContract;
use EscolaLms\Core\Services\AttachmentService;
use EscolaLms\Core\Services\Contracts\AttachmentServiceContract;
use EscolaLms\Core\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttachmentTest extends TestCase
{
    public function test_dependency(): void
    {
        $this->assertInstanceOf(AttachmentService::class, app(AttachmentServiceContract::class));
        $this->assertInstanceOf(AttachmentRepository::class, app(AttachmentRepositoryContract::class));
    }

    public function test_upload_attachment_api(): void
    {
        Storage::fake();
        $user = factory(User::class)->create();
        $this->response = $this->actingAs($user, 'api')->json(
            'POST',
            '/api/attachments',
            [
                'file' => UploadedFile::fake()->create('pdf.pdf')
            ]
        );

        $file = Attachment::find($this->response->getData());

        $this->response->assertStatus(201);
        Storage::assertExists($file->path);
    }
}
