<?php

namespace App\Listeners;

use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use EscolaSoft\Shopping\Events\OrderPaid;

class AttachOrderedCoursesToUser
{
    private CourseServiceContract $courseService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CourseServiceContract $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $this->courseService->attachOrderedCoursesToUser($event->getOrder()->items, $event->getUser());
    }
}
