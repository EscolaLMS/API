<?php

namespace App\Services\EscolaLMS\Media;

use App\Dto\CurriculumLectureQuizDto;
use App\Services\EscolaLMS\Contracts\MediaContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VideoMedia extends Media implements MediaContract
{
    public function get()
    {
        if ($this->medium) {
            return $this->medium;
        }

        return $this->medium = $this->courseVideoRepository->find($this->lecture->media);
    }

    /**
     * @param UploadedFile $file
     * @param Authenticatable $user
     * @return $this
     */
    public function create($file, Authenticatable $user): self
    {
        $courseVideos = $this->courseService->storeVideoForCourse($file, $this->lecture->section->course, $user);

        $this->delete(true);

        $lectureQuizRowDto = CurriculumLectureQuizDto::instantiateFromMedia($this, $courseVideos);

        $this->lecture = $this->courseService->updateLectureQuizRow($lectureQuizRowDto, $this->lecture);

        return $this;
    }

    public function getUrl(): string
    {
        return Storage::url('course/' . $this->medium->course_id . '/' . $this->medium->video_title . '.' . $this->medium->video_type);
    }
}
