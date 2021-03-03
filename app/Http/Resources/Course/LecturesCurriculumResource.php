<?php

namespace App\Http\Resources\Course;

use App\Models\CurriculumLecturesQuiz;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LecturesCurriculumResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->filter(function (CurriculumLecturesQuiz $lecture) {
            return $lecture->publish;
        })->transform(function (CurriculumLecturesQuiz $lecture) {
            return new LectureCurriculumResource($lecture);
        })->toArray();
    }
}
