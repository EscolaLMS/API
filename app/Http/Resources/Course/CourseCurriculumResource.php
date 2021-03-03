<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseCurriculumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->withoutWrapping();

        return [
//            'course' => new CourseResource($this->resource), // this is commented, to be here back if there will be any issue on frontend
            'sections' => new SectionsCurriculumResource($this->resource->getSections())
        ];
    }
}
