<?php

namespace App\Models;

use EscolaLms\Cart\Contracts\Productable;
use EscolaLms\Cart\Contracts\ProductableTrait;

class Course extends \EscolaLms\Courses\Models\Course implements Productable
{
    use ProductableTrait;
}
