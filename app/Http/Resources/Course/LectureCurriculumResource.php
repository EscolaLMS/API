<?php

namespace App\Http\Resources\Course;

use App\Services\EscolaLMS\Media\Media;
use Illuminate\Http\Resources\Json\JsonResource;

class LectureCurriculumResource extends JsonResource
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
            'lecture_quiz_id' => $this->resource->getKey(),
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'contenttext' => $this->contenttext,
            'image' => $this->image,
            'media' => Media::make($this->resource)->getResource(),
            'sort_order' => $this->sort_order,
            'publish' => $this->publish,
            'resources' => new FilesResource($this->resourceFiles),
            'duration' => $this->duration,
            'section_id' => $this->section_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
