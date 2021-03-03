<?php

namespace App\Dto;

use App\Models\Category;
use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use EscolaSoft\EscolaLms\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class CourseCreateDto extends CourseDto implements DtoContract, InstantiateFromRequest
{
    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            null,
            $request->input('course_title'),
            self::getInstructorFromRequest($request),
            Category::find($request->input('category_id')),
            $request->input('instruction_level_id'),
            $request->input('keywords'),
            $request->input('overview'),
            $request->input('duration'),
            $request->input('price'),
            $request->input('strike_out_price'),
            (bool)$request->input('is_active'),
            $request->input('tags'),
            $request->input('summary'),
            $request->input('instructor_income') ?? 0,
        );
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        $slug = $this->getTitle();
        return \App\Library\EscolaHelpers::slugify($slug, 'courses', 'course_slug');
    }
}
