<?php

namespace App\Dto;

use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use EscolaSoft\EscolaLms\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class CategoryCreateDto implements DtoContract, InstantiateFromRequest
{
    private ?string $id;
    private ?string $name;
    private ?UploadedFile $icon;
    private ?string $icon_class;
    private bool $isActive;
    private ?int $parentId;

    public function __construct(?string $id, string $name, ?UploadedFile $icon, string $icon_class, bool $isActive, ?int $parentId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->icon = $icon;
        $this->icon_class = $icon_class;
        $this->isActive = $isActive;
        $this->parentId = $parentId;
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->input('category_id'),
            $request->input('name'),
            $request->file('icon'),
            $request->input('icon_class'),
            $request->boolean('is_active'),
            $request->input('parent_id'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'icon_class' => $this->getIconClass(),
            'is_active' => $this->getIsActive(),
            'parent_id' => $this->getIsActive(),
        ];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?UploadedFile
    {
        return $this->icon;
    }

    public function getIconClass(): ?string
    {
        return $this->icon_class;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }
}
