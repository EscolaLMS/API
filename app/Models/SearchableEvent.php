<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SearchableEvent extends Model
{
    public function event(): MorphTo
    {
        return $this->morphTo();
    }
}
