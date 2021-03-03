<?php

namespace App\Http\Requests\API;

use App\Enum\ProgressStatus;
use App\Rules\ValidEnum;
use InfyOm\Generator\Request\APIRequest;

class CourseH5pProgressAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event' => 'string|required',
            'data' => 'array',
        ];
    }
}
