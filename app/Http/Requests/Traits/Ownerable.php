<?php

namespace App\Http\Requests\Traits;

use EscolaLms\Core\Enums\UserRole;

trait Ownerable
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole(UserRole::ADMIN) || $this->isMyCourse();
    }

    /**
     * @return bool
     */
    private function isMyCourse(): bool
    {
        return $this->route('course')->instructor->user->getKey() === $this->user()->getKey();
    }
}
