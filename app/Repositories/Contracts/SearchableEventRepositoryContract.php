<?php

namespace App\Repositories\Contracts;

use EscolaLms\Core\Dtos\OrderDto;
use Illuminate\Database\Query\Builder;

interface SearchableEventRepositoryContract
{
    public function eventsQuery(OrderDto $orderDto): Builder;
}
