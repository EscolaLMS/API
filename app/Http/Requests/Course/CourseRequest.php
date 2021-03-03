<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\Traits\Ownerable;
use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    use Ownerable;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_title' => ['required', 'string', 'max:50'],
            'category_id' => 'required',
            'instruction_level_id' => 'required',
            'price' => ['numeric', 'min:0'],
            'strike_out_price' => ['nullable', 'numeric', 'gte:price'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'instructor_income' => ['nullable', 'numeric', 'min:0']
        ];
    }
}
