<?php

namespace App\Http\Requests;

use EscolaLms\Core\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class VideoUploadRequest extends FormRequest
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
            'lecturevideo' => ['required', 'mimes:mp4,mov,avi,flv']
        ];
    }
}
