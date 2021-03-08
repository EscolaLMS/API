<?php


namespace App\Dto;

use App\Enum\LectureType;
use App\Models\Contracts\MediaModelContract;
use App\Models\CurriculumSection;
use App\Services\EscolaLMS\Contracts\MediaContract;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromMedia;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromModel;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class CurriculumLectureQuizDto implements DtoContract, InstantiateFromRequest, InstantiateFromMedia
{
    private CurriculumSection $section;
    private ?UploadedFile $image;
    private int $type;
    private ?string $title;
    private ?string $description;
    private ?string $contentText;
    private ?string $media;
    private ?string $mediaType;
    private int $sortOrder;
    private ?array $resources;
    private bool $publish;
    private ?string $duration;

    /**
     * CurriculumLectureQizDto constructor.
     * @param CurriculumSection $section
     * @param int $type
     * @param string|null $title
     * @param string|null $description
     * @param string|null $contentText
     * @param string|null $media
     * @param string|null $mediaType
     * @param int $sortOrder
     * @param array|null $resources
     * @param bool $publish
     */
    public function __construct(CurriculumSection $section, int $type = LectureType::SECTION, int $sortOrder = 0, ?string $title = null, ?string $description = null, ?string $contentText = null, ?string $media = null, ?string $mediaType = null, ?array $resources = null, bool $publish = false, ?string $duration = null, ?UploadedFile $image = null)
    {
        $this->section = $section;
        $this->type = $type;
        $this->title = $title;
        $this->description = $description;
        $this->contentText = $contentText;
        $this->media = $media;
        $this->mediaType = $mediaType;
        $this->sortOrder = $sortOrder;
        $this->resources = $resources;
        $this->publish = $publish;
        $this->duration = $duration;
        $this->image = $image;
    }

    public function toArray(): array
    {
        return [
            'section_id' => $this->getSection()->getKey(),
            'type' => $this->getType(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'contenttext' => $this->getContentText(),
            'media' => $this->getMedia(),
            'media_type' => $this->getMediaType(),
            'sort_order' => $this->getSortOrder(),
            'publish' => $this->isPublish(),
            'resources' => $this->getResources(),
            'duration' => $this->getDuration(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->route('curriculumSection'),
            LectureType::SECTION,
            $request->get('position', 0),
            $request->get('lecture'),
            null,
            null,
            null,
            null,
            null,
            false,
            $request->get('duration'),
            $request->file('image')
        );
    }

    public static function instantiateFromRequestWithModel(Request $request, Model $model): self
    {
        return new self(
            $request->route('curriculumSection'),
            LectureType::SECTION,
            $request->get('position', $model->sort_order),
            $request->get('lecture', $model->title),
            $request->get('description', $model->description),
            $request->get('contenttext', $model->contenttext),
            $request->get('media', $model->media),
            $request->get('media_type', $model->media_type),
            $request->get('resources', $model->resources),
            $request->get('publish', $model->publish),
            $request->get('duration', $model->duration),
            $request->file('image')
        );
    }

    public static function instantiateFromMedia(MediaContract $media, MediaModelContract $file): self
    {
        $lecture = $media->getLecture();

        return new self(
            $lecture->section,
            LectureType::SECTION,
            $lecture->sort_order ?? 0,
            $lecture->title ?? null,
            $lecture->description ?? null,
            $lecture->contenttext ?? $file->url,
            $file->getKey(),
            $lecture->media_type ?? null,
            $lecture->resources ?? null,
            $lecture->publish ?? false,
            $lecture->duration ?? null
        );
    }

    /**
     * @return CurriculumSection
     */
    public function getSection(): CurriculumSection
    {
        return $this->section;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getContentText(): ?string
    {
        return $this->contentText;
    }

    /**
     * @return string|null
     */
    public function getMedia(): ?string
    {
        return $this->media;
    }

    /**
     * @return string|null
     */
    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    /**
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * @return array|null
     */
    public function getResources(): ?array
    {
        return $this->resources;
    }

    /**
     * @return bool
     */
    public function isPublish(): bool
    {
        return $this->publish;
    }

    /**
     * @return string|null
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * @return UploadedFile|null
     */
    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }
}
