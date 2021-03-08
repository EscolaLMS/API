<?php

namespace EscolaLms\Core\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CreateAttachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (bool)$this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => ['file', 'mimes:jpeg,bmp,png,gif,svg,pdf'],
        ];
    }
}
