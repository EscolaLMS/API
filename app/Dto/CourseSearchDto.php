<?php

namespace App\Dto;

use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class CourseSearchDto implements InstantiateFromRequest
{
    private string $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->get('query', '')
        );
    }
}
