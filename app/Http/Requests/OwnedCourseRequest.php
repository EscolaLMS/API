<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OwnedCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->userHasCourse();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return mixed
     */
    private function userHasCourse()
    {
        return $this->user()->courses()->where('course_id', $this->route('course')->getKey())->exists();
    }
}
