<?php

namespace App\Services\EscolaLMS\Media;

use App\Dto\CurriculumLectureQuizDto;
use App\Enum\LectureType;
use App\Services\EscolaLMS\Contracts\MediaContract;
use Illuminate\Contracts\Auth\Authenticatable;

class NullableMedia extends Media implements MediaContract
{
    public function create($content, Authenticatable $user): MediaContract
    {
        $lectureQuizRowDto = new CurriculumLectureQuizDto(
            $this->lecture->section,
            LectureType::SECTION,
            $this->lecture->sort_order ?? 0,
            $this->lecture->title ?? '',
            $this->lecture->description ?? null,
            $this->lecture->contenttext ?? '',
            $this->lecture->media ?? null,
            $this->lecture->media_type ?? null,
            $this->lecture->resources ?? null,
            $this->lecture->publish ?? null,
            $this->lecture->duration ?? null
        );

        $this->lecture = $this->courseService->updateLectureQuizRow($lectureQuizRowDto, $this->lecture);

        return $this;
    }

    public function get()
    {
        return null;
    }

    public function getResource(): array
    {
        return [];
    }

    public function getUrl(): string
    {
        return '';
    }
}
