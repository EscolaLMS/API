<?php

namespace App\Dto;

use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BlogCreateDto implements DtoContract, InstantiateFromRequest
{
    private ?string $id;
    private string $title;
    private string $description;
    private bool $isActive;
    private ?UploadedFile $image;
    private ?string $imageBase64;
    private ?string $oldImage;

    public function __construct(?string $id, string $title, string $description, bool $isActive, ?UploadedFile $image, ?string $imageBase64, ?string $oldImage)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->isActive = $isActive;
        $this->image = $image;
        $this->imageBase64 = $imageBase64;
        $this->oldImage = $oldImage;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'is_active' => $this->getIsActive(),
            'image' => optional($this->getImage())->path(),
            'imageBase64' => $this->getImageBase64(),
            'oldImage' => $this->getOldImage(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->input('blog_id'),
            $request->input('blog_title'),
            $request->input('description'),
            $request->boolean('is_active'),
            $request->file('blog_image'),
            $request->input('blog_image_base64'),
            $request->input('old_blog_image'),
        );
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function getImageBase64(): ?string
    {
        return $this->imageBase64;
    }

    public function getOldImage(): ?string
    {
        return $this->oldImage;
    }
}
