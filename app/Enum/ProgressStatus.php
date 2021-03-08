<?php

namespace App\Enum;

use EscolaLms\Core\Enums\BasicEnum;

class ProgressStatus extends BasicEnum
{
    const INCOMPLETE = 0;
    const COMPLETE = 1;
    const IN_PROGRESS = 2;
}
