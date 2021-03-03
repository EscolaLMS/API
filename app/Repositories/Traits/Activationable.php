<?php

namespace App\Repositories\Traits;

use Illuminate\Support\Collection;

trait Activationable
{
    public function getActive(): Collection
    {
        return $this->model->newQuery()->where('is_active', true)->get();
    }
}
