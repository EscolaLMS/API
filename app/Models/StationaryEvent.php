<?php

namespace App\Models;

use EscolaLms\Cart\Contracts\Productable;
use EscolaLms\Cart\Contracts\ProductableTrait;

class StationaryEvent extends \EscolaLms\StationaryEvents\Models\StationaryEvent implements Productable
{
    use ProductableTrait;
}
