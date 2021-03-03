<?php

namespace Features\Traits;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

trait CreateUsers
{
    public function createUserWithRole(array $userPayload, string $role): User
    {
        $user = factory(User::class)->create($userPayload);
        $user->assignRole($role);

        event(new Registered($user));

        return $user;
    }

    public function findInstructorByEmail(string $email): Instructor
    {
        $instructor = User::where('email', $email)->first()->instructor ?? null;

        if (!is_null($instructor)) {
            return $instructor;
        }

        $user = $this->createUserWithRole([
            'email' => $email
        ], 'instructor');

        return factory(Instructor::class)->create([
            'user_id' => $user->getKey()
        ]);
    }
}
