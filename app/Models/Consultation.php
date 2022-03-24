<?php

namespace App\Models;

use EscolaLms\Cart\Contracts\Productable;
use EscolaLms\Cart\Contracts\ProductableTrait;
use EscolaLms\Core\Models\User;

class Consultation extends \EscolaLms\Consultations\Models\Consultation implements Productable
{
    use ProductableTrait;

    public function attachToUser(User $user, int $quantity = 1): void
    {
        for ($i = 1; $i <= $quantity; $i++) {
            parent::attachToUser($user);
        }
    }
}
