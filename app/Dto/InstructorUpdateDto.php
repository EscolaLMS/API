<?php

namespace App\Dto;

use App\Models\User;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class InstructorUpdateDto implements DtoContract, InstantiateFromRequest
{
    private $user;
    private $data;
    private $files;

    public function __construct(User $user, array $data, array $files)
    {
        $this->user = $user;
        $this->data = $data;
        $this->files = $files;
    }

    public function toArray(): array
    {
        return [
            'user' => $this->getUser(),
            'data' => $this->getData(),
            'files' => $this->getFiles(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->user(),
            $request->all(),
            $request->allFiles(),
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

    public function getFiles(): array
    {
        return $this->files;
    }
}
