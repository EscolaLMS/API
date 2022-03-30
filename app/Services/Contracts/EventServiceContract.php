<?php

namespace App\Services\Contracts;

use EscolaLms\Core\Dtos\OrderDto;
use Illuminate\Support\Collection;

interface EventServiceContract
{
    public function getEventsList(OrderDto $orderDto): Collection;
}
