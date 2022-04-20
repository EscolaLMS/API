<?php

namespace App\Services;

use App\Repositories\Contracts\SearchableEventRepositoryContract;
use App\Services\Contracts\SearchableEventServiceContract;
use EscolaLms\Core\Dtos\OrderDto;
use Illuminate\Database\Query\Builder;

class SearchableEventService implements SearchableEventServiceContract
{
    private SearchableEventRepositoryContract $searchableEventRepository;

    public function __construct(SearchableEventRepositoryContract $searchableEventRepository)
    {
        $this->searchableEventRepository = $searchableEventRepository;
    }

    public function getEventsList(OrderDto $orderDto): Builder
    {
        return $this->searchableEventRepository->eventsQuery($orderDto);
    }
}
