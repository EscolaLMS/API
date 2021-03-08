<?php

namespace EscolaLms\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Status extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->withoutWrapping();

        return ['status' => $this->resource];
    }
}
