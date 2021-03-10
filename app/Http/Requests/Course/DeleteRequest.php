<?php

namespace App\Http\Requests\Course;

use EscolaLms\Auth\Http\Requests\Traits\Ownerable;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
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
            //
        ];
    }
}
