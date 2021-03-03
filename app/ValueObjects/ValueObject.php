<?php

namespace App\ValueObjects;

use App\ValueObjects\Contracts\ValueObjectContract;

abstract class ValueObject implements ValueObjectContract
{
    public static function make(...$args): ValueObject
    {
        $app = app(static::class);
        $app->build(...$args);
        return $app;
    }

    abstract public function toArray(): array;
}
