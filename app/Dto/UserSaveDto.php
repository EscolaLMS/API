<?php

namespace App\Dto;

use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserSaveDto implements InstantiateFromRequest
{
    private string $firstName;
    private string $lastName;
    private ?string $email;
    private ?string $password;
    private bool $isActive;
    private array $roles;

    public function __construct(string $firstName, string $lastName, bool $isActive, array $roles, ?string $email = null, ?string $password = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->isActive = $isActive;
        $this->roles = $roles;
    }

    public function getUserAttributes(): array
    {
        $arr = [
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'is_active' => $this->getIsActive(),
        ];
        if ($this->email) {
            $arr['email'] = $this->getEmail();
        }
        if ($this->password) {
            $arr['password'] = $this->getPassword();
        }
        return $arr;
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('is_active', false),
            $request->input('roles', [UserRole::STUDENT]),
            $request->input('email'),
            $request->input('password'),
        );
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password ? Hash::make($this->password) : null;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
