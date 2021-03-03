<?php

namespace App\Http\Resources\Course;

use App\Models\CurriculumSection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseSectionsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function (CurriculumSection $curriculumSection) {
            return new CourseSectionResource($curriculumSection);
        })->toArray();
    }
}
