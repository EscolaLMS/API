<?php

namespace App\Http\Requests;

use App\Enum\OnboardingStatus;
use App\Enum\StatusEnum;
use EscolaLms\Core\Enum\UserRole;
use App\Http\Requests\Traits\WithRole;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class UsersListRequest extends FormRequest
{
    use WithRole;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->hasRole(UserRole::ADMIN);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'search' => ['nullable'],
            'role' => ['nullable', new EnumValue(UserRole::class, false)],
            'status' => ['nullable', new EnumValue(StatusEnum::class, false)],
            'onboarding' => ['nullable', new EnumValue(OnboardingStatus::class, false)],
            'from' => ['date', 'nullable'],
            'to' => ['date', 'nullable']
        ];
    }
}
