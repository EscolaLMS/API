<?php

namespace EscolaLms\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'id' => $this->resource->getKey(),
            'path' => $this->path,
            'url' => $this->url,
            'size' => $this->size,
            'filename' => $this->filename
        ];
    }
}
