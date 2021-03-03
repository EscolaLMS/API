<?php

namespace App\Enum;

use EscolaSoft\EscolaLms\Enums\BasicEnum;

class MediaType extends BasicEnum
{
    public const VIDEO = 0;
    public const AUDIO = 1;
    public const DOCUMENT = 2;
    public const TEXT = 3;
    // public const NOT_EXISTING = 4; // TODO: it looks, like MediaType:4 is not exists
    public const PRESENTATION = 5;
    public const INTERACTIVE = 6;
}
