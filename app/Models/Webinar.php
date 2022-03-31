<?php

namespace App\Models;

use EscolaLms\Cart\Contracts\Productable;
use EscolaLms\Cart\Contracts\ProductableTrait;
use EscolaLms\Core\Models\User;
use EscolaLms\Webinar\Events\WebinarUserAssigned;
use EscolaLms\Webinar\Events\WebinarUserUnassigned;

class Webinar extends \EscolaLms\Webinar\Models\Webinar implements Productable
{
    use ProductableTrait;

    public function attachToUser(User $user, int $quantity = 1): void
    {
        $this->users()->syncWithoutDetaching($user->getKey());
        event(new WebinarUserAssigned($user, $this));
    }

    public function detachFromUser(User $user, int $quantity = 1): void
    {
        $this->users()->detach($user->getKey());
        event(new WebinarUserUnassigned($user, $this));
    }
}
