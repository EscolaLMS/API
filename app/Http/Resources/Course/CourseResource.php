<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\User\AuthorResource;
use App\Models\Course;
use App\ValueObjects\CourseContent;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseResource extends JsonResource
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

        if ($this->resource instanceof Course) {
            $this->resource = CourseContent::make($this->resource);
        }

        $course = $this->resource->getCourse();

        return [
            'id' => $course->getKey(),
            'course_slug' => $course->course_slug,
            'instruction_level_id' => $course->instruction_level_id,
            'instruction_level' => $course->level->level ?? null,
            'course_title' => $course->course_title,
            'keywords' => $course->keywords,
            'video' => $course->course_video ? Storage::url($course->course_video) : null,
            'overview' => $course->overview,
            'duration' => $course->duration,
            'price' => $course->price,
            'strike_out_price' => $course->strike_out_price,
            'active' => $course->is_active,
            'tags' => new CourseTagsResource($course->tags),
            'shortDesc' => [
                'title' => $course->course_title ?? "What you’ll learn",
                'description' => $course->overview,
                'image' => $course->course_image,
                'thumb' => $course->thumb_image,
            ],
            'author' => new AuthorResource($course->instructor),
            'related' => new CoursesResource($this->resource->getRelated()),
            'category_id' => $course->category_id,
            'instructor_id' => $course->instructor_id,
            'is_active' => $course->is_active,
            'created_at' => $course->created_at,
            'updated_at' => $course->updated_at
        ];
    }
}
