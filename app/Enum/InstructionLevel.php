<?php

namespace App\Enum;

use EscolaSoft\EscolaLms\Enums\BasicEnum;

class InstructionLevel extends BasicEnum
{
    public const INTRODUCTORY = 1;
    public const INTERMEDIATE = 2;
    public const ADVANCED = 3;
    public const COMPREHENSIVE = 4;

    protected static function singleDetail(string $const, $value): \stdClass
    {
        $enum = new \stdClass();
        $enum->const = $const;
        $enum->id = $value;
        $enum->level = self::getDescription($value);
        return $enum;
    }
}
