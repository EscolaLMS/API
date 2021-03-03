<?php

namespace App\Http\Resources\Course;

use App\Models\CourseFiles;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FilesResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function (CourseFiles $file) {
            $data = $file->toArray();
            unset($data['course']);

            return $data;
        })->toArray();
    }
}
