<?php

namespace App\Http\Resources\Course;

use App\ValueObjects\CourseContent;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressResource extends JsonResource
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
            'course' => new CourseResource(CourseContent::make($this->resource)),
            'progress' => $this->progress->toArray(),
            'spent_time' => $this->progress->getTotalSpentTime(),
            'finish_date' => $this->progress->getFinishDate(),
        ];
    }
}
