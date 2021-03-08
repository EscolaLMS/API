<?php

namespace App\ValueObjects\Contracts;

use App\ValueObjects\ValueObject;
use EscolaLms\Core\Dtos\Contracts\DtoContract;

interface ValueObjectContract extends DtoContract
{
    public static function make(): ValueObject;
}
