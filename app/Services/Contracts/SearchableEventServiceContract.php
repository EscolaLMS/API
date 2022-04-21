<?php

namespace App\Services\Contracts;

use EscolaLms\Core\Dtos\OrderDto;
use Illuminate\Database\Query\Builder;

interface SearchableEventServiceContract
{
    public function getEventsList(OrderDto $orderDto): Builder;
}
