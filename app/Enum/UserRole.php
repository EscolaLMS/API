<?php

namespace App\Enum;

use EscolaSoft\EscolaLms\Enums\BasicEnum;

class UserRole extends BasicEnum
{
    const STUDENT = 'student';
    const INSTRUCTOR = 'instructor';
    const ADMIN = 'admin';
}
