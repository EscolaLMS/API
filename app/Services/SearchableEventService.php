<?php

namespace App\Services;

use App\Enum\EventOrderByEnum;
use App\Repositories\Contracts\SearchableEventRepositoryContract;
use App\Repositories\Criteria\OrderCriterion;
use App\Services\Contracts\SearchableEventServiceContract;
use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Repositories\Criteria\Primitives\DateCriterion;
use Illuminate\Database\Eloquent\Builder;

class SearchableEventService implements SearchableEventServiceContract
{
    private SearchableEventRepositoryContract $searchableEventRepository;

    public function __construct(SearchableEventRepositoryContract $searchableEventRepository)
    {
        $this->searchableEventRepository = $searchableEventRepository;
    }

    public function getEventsList(OrderDto $orderDto): Builder
    {
        $criteria = [];
        $now = now();

        if ($orderDto->getOrderBy() === EventOrderByEnum::NEXT) {
            $criteria[] = new DateCriterion('start_date', $now, '>=');
            $criteria[] = new OrderCriterion('start_date', 'asc');
        }

        if ($orderDto->getOrderBy() === EventOrderByEnum::PAST) {
            $criteria[] = new DateCriterion('start_date', $now, '<');
            $criteria[] = new OrderCriterion('start_date', 'desc');
        }

        return $this->searchableEventRepository->queryWithAppliedCriteria($criteria);
    }
}
