<?php

namespace Tests;

use EscolaLms\Core\Enums\UserRole;
use App\Models\User;

trait CreatesUsers
{
    protected function makeStudent(array $data = [], bool $create = true): User
    {
        $user = $this->create($data, $create);
        $user->assignRole(UserRole::STUDENT);
        return $user;
    }

    protected function makeInstructor(array $data = [], bool $create = true): User
    {
        $user = $this->create($data, $create);
        $user->assignRole(UserRole::INSTRUCTOR);
        return $user;
    }

    protected function makeAdmin(array $data = [], bool $create = true): User
    {
        $user = $this->create($data, $create);
        $user->assignRole(UserRole::ADMIN);
        return $user;
    }

    private function create(array $data = [], bool $create = true): User
    {
        if ($create) {
            $user = factory(User::class)->create($data);
        } else {
            $user = factory(User::class)->make($data);
        }
        return $user;
    }
}
