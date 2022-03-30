<?php

namespace App\Http\Resources;

use EscolaLms\StationaryEvents\Http\Resources\StationaryEventResource;
use EscolaLms\StationaryEvents\Models\StationaryEvent;
use EscolaLms\Webinar\Http\Resources\WebinarSimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->resource instanceof StationaryEvent
            ? StationaryEventResource::make($this->resource)
            : WebinarSimpleResource::make($this->resource);
    }
}
