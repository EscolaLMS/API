<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_video' => ['required', 'mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime']
        ];
    }
}
