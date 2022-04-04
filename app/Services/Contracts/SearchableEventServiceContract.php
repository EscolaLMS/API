<?php

namespace App\Services\Contracts;

use EscolaLms\Core\Dtos\OrderDto;
use Illuminate\Database\Eloquent\Builder;

interface SearchableEventServiceContract
{
    public function getEventsList(OrderDto $orderDto): Builder;
}
