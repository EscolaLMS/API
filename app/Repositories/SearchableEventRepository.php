<?php

namespace App\Repositories;

use App\Enum\EventOrderByEnum;
use App\Repositories\Contracts\SearchableEventRepositoryContract;
use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\StationaryEvents\Models\StationaryEvent;
use EscolaLms\Webinar\Models\Webinar;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SearchableEventRepository implements SearchableEventRepositoryContract
{
    public function eventsQuery(OrderDto $orderDto): Builder
    {
        $query = $this->getWebinarsQuery($orderDto)
            ->union($this->getStationaryEventsQuery($orderDto));

        if ($orderDto->getOrderBy() === null) {
            $query = $query->orderBy('created_at');
        }

        if ($orderDto->getOrderBy() === EventOrderByEnum::NEXT) {
            $query = $query->orderBy('start_date');
        }

        if ($orderDto->getOrderBy() === EventOrderByEnum::PAST) {
            $query = $query->orderByDesc('start_date');
        }

        return $query;
    }

    private function getStationaryEventsQuery(OrderDto $orderDto): Builder
    {
        $stationaryEventClass = $this->prepareClassName(StationaryEvent::class);

        $query = DB::table('stationary_events')
            ->selectRaw(
                "id AS event_id,
                '{$stationaryEventClass}' AS event_type,
                started_at AS start_date,
                finished_at AS end_date,
                created_at"
            )->whereIn('status', ['published', 'published_unactivated']);

        if ($orderDto->getOrderBy() === EventOrderByEnum::PAST) {
            $query = $query->whereDate('started_at', '<', now());
        } else {
            $query = $query->whereDate('started_at', '>=', now());
        }

        return $query;
    }

    private function getWebinarsQuery(OrderDto $orderDto): Builder
    {
        $webinarClass = $this->prepareClassName(Webinar::class);

        $query = DB::table('webinars')
            ->selectRaw(
                "id AS event_id,
                '{$webinarClass}' AS event_type,
                active_from AS start_date,
                active_to AS end_date,
                created_at"
            )->whereIn('status', ['published', 'published_unactivated']);

        if ($orderDto->getOrderBy() === EventOrderByEnum::PAST) {
            $query = $query->whereDate('active_from', '<', now());
        } else {
            $query = $query->whereDate('active_from', '>=', now());
        }

        return $query;
    }

    private function prepareClassName(string $className): string
    {
        if (DB::connection() instanceof MySqlConnection) {
            $className = str_replace('\\', '\\\\', $className);
        }

        return $className;
    }
}
