<?php

namespace App\Http\Requests;

use EscolaLms\Core\Enum\UserRole;
use App\Http\Requests\Traits\WithRole;
use Illuminate\Foundation\Http\FormRequest;

class H5pEmbedRequest extends FormRequest
{
    use WithRole;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // TODO: here we have issue, that $this->hasValidSignature() dosnt work - we need to test issue and fix it!
        return $this->hasRole([UserRole::ADMIN, UserRole::INSTRUCTOR]) || $this->hasValidSignature();
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
}
