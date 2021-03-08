<?php

namespace App\Http\Requests;

use EscolaLms\Core\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class LectureSortRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasAnyRole(UserRole::ADMIN, UserRole::INSTRUCTOR);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lecturequizdata' => ['array'],
            'lecturequizdata.*.id' => ['required', 'exists:curriculum_lectures_quiz,lecture_quiz_id'],
            'lecturequizdata.*.sectionid' => ['required', 'exists:curriculum_sections,section_id'],
            'lecturequizdata.*.position' => ['required', 'integer']
        ];
    }
}
