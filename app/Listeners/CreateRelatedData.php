<?php

namespace App\Listeners;

use App\Repositories\Contracts\InstructorRepositoryContract;
use EscolaLms\Core\Enums\UserRole;
use App\Services\Contracts\UserServiceContract;
use Illuminate\Auth\Events\Registered;

class CreateRelatedData
{
    private InstructorRepositoryContract $instructorRepository;

    /**
     * CreateRelatedData constructor.
     * @param InstructorRepositoryContract $instructorRepository
     */
    public function __construct(InstructorRepositoryContract $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
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
            $this->instructorRepository->createUsingUser($event->user);
        }
    }
}
