<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionCurriculumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'section_id' => $this->resource->getKey(),
            'title' => $this->title,
            'image' => $this->image,
            'sort_order' => $this->sort_order,
            'lectures' => new LecturesCurriculumResource($this->lectures),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
