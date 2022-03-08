<?php

namespace App\Models;

use EscolaLms\Cart\Contracts\Productable;
use EscolaLms\Cart\Contracts\ProductableTrait;

class Webinar extends \EscolaLms\Webinar\Models\Webinar implements Productable
{
    use ProductableTrait;
}
