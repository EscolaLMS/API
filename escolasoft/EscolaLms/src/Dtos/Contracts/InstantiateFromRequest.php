<?php


namespace EscolaLms\Core\Dtos\Contracts;

use Illuminate\Http\Request;

interface InstantiateFromRequest
{
    public static function instantiateFromRequest(Request $request): self;
}
