<?php

namespace App\Services\EscolaLMS\Media;

use App\Dto\CurriculumLectureQuizDto;
use App\Services\EscolaLMS\Contracts\MediaContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;

class DocumentMedia extends Media implements MediaContract
{
    /**
     * @param UploadedFile $file
     * @param Authenticatable $user
     * @return $this
     */
    public function create($file, Authenticatable $user): self
    {
        $courseFile = $this->courseService->storeDocumentForCourse($file, $this->lecture->section->course, $user);

        $this->delete(true);

        $lectureQuizRowDto = CurriculumLectureQuizDto::instantiateFromMedia($this, $courseFile);

        $this->lecture = $this->courseService->updateLectureQuizRow($lectureQuizRowDto, $this->lecture);

        return $this;
    }
}
