<?php

namespace App\Models;

use EscolaLms\Cart\Contracts\Productable;
use EscolaLms\Cart\Contracts\ProductableTrait;

class Consultation extends \EscolaLms\Consultations\Models\Consultation implements Productable
{
    use ProductableTrait;
}
