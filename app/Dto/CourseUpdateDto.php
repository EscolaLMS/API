<?php

namespace App\Dto;

use App\Models\Category;
use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use EscolaSoft\EscolaLms\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class CourseUpdateDto extends CourseDto implements DtoContract, InstantiateFromRequest
{
    public static function instantiateFromRequest(Request $request): self
    {
        $course = $request->route('course');

        if (!is_null($request->input('category_id'))) {
            $category = Category::find($request->input('category_id'));
        } else {
            $category = $course->category->getKey();
        }


        return new self(
            $course,
            $request->input('course_title', $course->course_title),
            self::getInstructorFromRequest($request),
            $category,
            $request->input('instruction_level_id', $course->instruction_level_id),
            $request->input('keywords', $course->keywords),
            $request->input('overview', $course->overview),
            $request->input('duration', $course->duration),
            $request->input('price', $course->price),
            $request->input('strike_out_price', $course->strike_out_price),
            (bool)$request->input('is_active', $course->is_active),
            $request->input('tags', $course->tags),
            $request->input('summary', $course->summary),
            self::getInstructorIncomeFromRequest($request) ?? $course->instructor_income,
        );
    }
}
