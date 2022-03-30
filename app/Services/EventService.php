<?php

namespace App\Services;

use App\Enum\EventOrderByEnum;
use App\Services\Contracts\EventServiceContract;
use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Repositories\Criteria\Primitives\DateCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\EqualCriterion;
use EscolaLms\StationaryEvents\Repositories\Contracts\StationaryEventRepositoryContract;
use EscolaLms\Webinar\Enum\WebinarStatusEnum;
use EscolaLms\Webinar\Repositories\Contracts\WebinarRepositoryContract;
use Illuminate\Support\Collection;

class EventService implements EventServiceContract
{
    private WebinarRepositoryContract $webinarRepository;
    private StationaryEventRepositoryContract $stationaryEventRepository;

    public function __construct(
        WebinarRepositoryContract $webinarRepository,
        StationaryEventRepositoryContract $stationaryEventRepository
    )
    {
        $this->webinarRepository = $webinarRepository;
        $this->stationaryEventRepository = $stationaryEventRepository;
    }

    public function getEventsList(OrderDto $orderDto): Collection
    {
        $stationaryEventCriteria = [];
        $webinarsCriteria[] = new EqualCriterion('status', WebinarStatusEnum::PUBLISHED);
        $now = now();

        if ($orderDto->getOrderBy() === EventOrderByEnum::NEXT) {
            $stationaryEventCriteria[] = new DateCriterion('started_at', $now, '>=');
            $webinarsCriteria[] = new DateCriterion('active_from', $now, '>=');
        }

        if ($orderDto->getOrderBy() === EventOrderByEnum::PAST) {
            $stationaryEventCriteria[] = new DateCriterion('started_at', $now, '<');
            $webinarsCriteria[] = new DateCriterion('active_from', $now, '<');
        }

        $stationaryEvents = $this->stationaryEventRepository->searchByCriteria($stationaryEventCriteria);
        $webinars = $this->webinarRepository->searchByCriteria($webinarsCriteria);

        return $this->sortCollection(
            $stationaryEvents->toBase()->merge($webinars),
            $orderDto->getOrderBy()
        );
    }

    private function sortCollection(Collection $collection, ?string $orderBy): Collection
    {
        return $collection->when($orderBy === EventOrderByEnum::NEXT, function (Collection $collection) {
            return $collection->sortBy(function ($event) {
                return $event->started_at ?? $event->active_from;
            });
        })->when($orderBy === EventOrderByEnum::PAST, function (Collection $collection) {
            return $collection->sortByDesc(function ($event) {
                return $event->started_at ?? $event->active_from;
            });
        })->when($orderBy === null, function (Collection $collection) {
            return $collection->sortBy('created_at');
        })->values();
    }
}
