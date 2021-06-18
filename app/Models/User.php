<?php

namespace App\Models;

//use EscolaLms\Core\Models\User as CoreUser;
use EscolaLms\Auth\Models\User as CoreUser;
use EscolaLms\Payments\Concerns\Billable;
use EscolaLms\Payments\Contracts\Billable as ContractsBillable;

// TODO: make user extendable from core + add all traits
class User extends CoreUser implements ContractsBillable
{
    use Billable;
}
