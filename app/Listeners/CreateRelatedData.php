<?php

namespace App\Listeners;

use App\Enum\UserRole;
use App\Services\Contracts\UserServiceContract;
use Illuminate\Auth\Events\Registered;

class CreateRelatedData
{
    private UserServiceContract $userService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserServiceContract $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user->hasAnyRole(UserRole::ADMIN, UserRole::INSTRUCTOR)) {
            $this->userService->activateInstructor($event->user);
        }
    }
}
