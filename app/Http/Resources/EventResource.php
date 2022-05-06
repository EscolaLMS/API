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
        $event = $this->event;
        $fields = $event instanceof StationaryEvent
            ? StationaryEventResource::make($event)->toArray($request)
            : WebinarSimpleResource::make($event)->toArray($request);
        return array_merge($fields, [
            'model' => $this->event_type,
        ]);
    }
}
