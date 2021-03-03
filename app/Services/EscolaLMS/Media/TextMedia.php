<?php

namespace App\Services\EscolaLMS\Media;

use App\Dto\CurriculumLectureQuizDto;
use App\Enum\LectureType;
use App\Enum\MediaType;
use App\Services\EscolaLMS\Contracts\MediaContract;
use Illuminate\Contracts\Auth\Authenticatable;

class TextMedia extends Media implements MediaContract
{
    public function get()
    {
        return $this->lecture->contenttext;
    }

    public function getResource(): array
    {
        return [
            'content' => $this->get(),
            'type' => MediaType::getName($this->lecture->media_type)
        ];
    }

    /**
     * @param string $text
     * @param Authenticatable $user
     * @return $this
     */
    public function create($text, Authenticatable $user): self
    {
        $lectureQuizRowDto = new CurriculumLectureQuizDto(
            $this->lecture->section,
            LectureType::SECTION,
            $this->lecture->sort_order ?? 0,
            $this->lecture->title ?? 'Text',
            $this->lecture->description ?? null,
            $text,
            null,
            $this->lecture->media_type,
            $this->lecture->resources ?? null,
            $this->lecture->publish ?? false,
            $this->lecture->duration ?? null
        );

        $this->lecture = $this->courseService->updateLectureQuizRow($lectureQuizRowDto, $this->lecture);

        return $this;
    }

    public function delete(bool $withoutTrashing = false): void
    {
        // can not delete text media
        return;
    }
}
