<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Swagger\EventAPISwagger;
use App\Http\Requests\ListEventRequest;
use App\Http\Resources\EventResource;
use App\Services\Contracts\SearchableEventServiceContract;
use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\StationaryEvents\Enum\ConstantEnum;
use Illuminate\Http\JsonResponse;

class EventAPIController extends EscolaLmsBaseController implements EventAPISwagger
{
    private SearchableEventServiceContract $eventService;

    public function __construct(SearchableEventServiceContract $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(ListEventRequest $request): JsonResponse
    {
        $orderDto = OrderDto::instantiateFromRequest($request);
        $events = $this->eventService
            ->getEventsList($orderDto)
            ->paginate($request->get('per_page') ?? ConstantEnum::PER_PAGE);

        return $this->sendResponseForResource(
            EventResource::collection($events),
            __('Events retrieved successfully')
        );
    }
}
