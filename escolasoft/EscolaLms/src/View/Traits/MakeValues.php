<?php

namespace EscolaSoft\EscolaLms\View\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

trait MakeValues
{
    private function makeValue(string $name, ?Model $model, ?string $value)
    {
        $model = $model ?? View::shared('model');
        $this->value = old($name, $model->$name ?? $value) ?? request()->get($name) ?? '';
    }
}
