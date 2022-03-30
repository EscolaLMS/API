<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Swagger\EventAPISwagger;
use App\Http\Requests\ListEventRequest;
use App\Http\Resources\EventResource;
use App\Services\Contracts\EventServiceContract;
use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\StationaryEvents\Enum\ConstantEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class EventAPIController extends EscolaLmsBaseController implements EventAPISwagger
{
    private EventServiceContract $eventService;

    public function __construct(EventServiceContract $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(ListEventRequest $request): JsonResponse
    {
        $orderDto = OrderDto::instantiateFromRequest($request);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $request->get('per_page') ?? ConstantEnum::PER_PAGE;

        $events = $this->eventService->getEventsList($orderDto);
        $result = new LengthAwarePaginator($events->forPage($page, $perPage), $events->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return $this->sendResponseForResource(
            EventResource::collection($result),
            __('Events retrieved successfully')
        );
    }
}
