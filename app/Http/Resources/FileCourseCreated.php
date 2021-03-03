<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileCourseCreated extends JsonResource
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
            'status' => true,
            'duration' => $this->duration,
            'file_title' => $this->file_title,
            'file_type' => $this->file_type,
            'file_link' => $this->url,
        ];
    }
}
