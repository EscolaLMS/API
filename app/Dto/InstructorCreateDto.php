<?php

namespace App\Dto;

use App\Models\User;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class InstructorCreateDto implements DtoContract, InstantiateFromRequest
{
    private $user;
    private $data;

    public function __construct(User $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'user' => $this->getUser(),
            'data' => $this->getData(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->user(),
            $request->all()
        );
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
