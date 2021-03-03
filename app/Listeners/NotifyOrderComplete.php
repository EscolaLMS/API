<?php

namespace App\Listeners;

use EscolaSoft\Shopping\Events\OrderPaid;

class NotifyOrderComplete
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $event->getUser()->notify(new \App\Notifications\OrderPaid($event->getUser(), $event->getOrder()));
    }
}
