<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class H5PContentResource extends JsonResource
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

        return [
            'status' => true,
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'library' => $this->library,
            'library_id' => $this->library_id,
            //'parameters' => $this->parameters,
            //'filtered' => $this->filtered,
            'slug' => $this->slug,
            'embed_type' => $this->embed_type,
            'disable' => $this->disable,
            'content_type' => $this->content_type,
            'author' => $this->author,
            'license' => $this->license,
            'keywords' => $this->keywords,
            'description' => $this->description,
        ];
    }
}
