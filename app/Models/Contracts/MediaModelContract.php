<?php

namespace App\Models\Contracts;

interface MediaModelContract
{
    public function getUrlAttribute(): string;

    public function getPathAttribute(): string;
}
