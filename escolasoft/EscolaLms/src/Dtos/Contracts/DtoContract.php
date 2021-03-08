<?php


namespace EscolaLms\Core\Dtos\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface DtoContract extends Arrayable
{
    public function toArray(): array;
}
