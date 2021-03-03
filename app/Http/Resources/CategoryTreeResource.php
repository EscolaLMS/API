<?php

namespace App\Http\Resources;

class CategoryTreeResource extends CategoryResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request) + [
            'subcategories' => self::collection($this->children),
        ];
    }
}
