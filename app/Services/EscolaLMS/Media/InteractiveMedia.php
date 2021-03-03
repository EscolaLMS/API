<?php

namespace App\Services\EscolaLMS\Media;

use App\Dto\CurriculumLectureQuizDto;
use App\Enum\LectureType;
use App\Services\EscolaLMS\Contracts\MediaContract;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\URL;

class InteractiveMedia extends Media implements MediaContract
{
    public function get()
    {
        if ($this->medium) {
            return $this->medium;
        }

        return $this->medium = $this->h5pContentRepository->find($this->lecture->media);
    }

    public function getUrl(): string
    {
        return URL::temporarySignedRoute('h5p.user.embed', Carbon::now()->addMonths(3), ['id' => $this->lecture->media]);
    }

    /**
     * @param string $text
     * @param Authenticatable $user
     * @return $this
     */
    public function create($media, Authenticatable $user): self
    {
        $lectureQuizRowDto = new CurriculumLectureQuizDto(
            $this->lecture->section,
            LectureType::SECTION,
            $this->lecture->sort_order ?? 0,
            $this->lecture->title ?? 'Text',
            $this->lecture->description ?? null,
            $this->lecture->media ?? null,
            $media,
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
