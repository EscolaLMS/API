<?php

namespace App\Http\Requests;

use EscolaLms\Core\Enum\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class SectionSortRequest extends FormRequest
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
            'sectiondata' => ['array'],
            'sectiondata.*.id' => ['required', 'exists:curriculum_sections,section_id'],
            'sectiondata.*.position' => ['required', 'integer']
        ];
    }
}
