<?php

namespace App\Http\Requests\Traits;

use EscolaLms\Core\Enum\UserRole;

trait WithRole
{
    private function hasRole($roles): bool
    {
        if (is_null($this->user())) {
            return false;
        }

        return $this->user()->hasRole($roles);
    }

    private function isEditorUser(): bool
    {
        return $this->hasRole([UserRole::ADMIN, UserRole::INSTRUCTOR]);
    }
}
