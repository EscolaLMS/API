<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->withoutWrapping();
        $file = $this->resource;

        if (!empty($file->file_name)) {
            if ($file->file_type != 'link') {
                $file->url = Storage::url('course/' . $file->course_id . '/' . $file->file_name . '.' . $file->file_extension);
            } else {
                $file->url = $file->file_name;
            }
        }

        $data = $file->toArray();
        unset($data['course']);

        return $data;
    }
}
