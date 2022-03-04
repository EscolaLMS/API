<?php

namespace App\Models;

//use EscolaLms\Core\Models\User as CoreUser;
use EscolaLms\Auth\Models\User as CoreUser;
use EscolaLms\Cart\Models\Contracts\CanOrder as ContractsCanOrder;
use EscolaLms\Cart\Models\Traits\CanOrder;
use EscolaLms\Courses\Models\Traits\HasCourses;
use EscolaLms\Payments\Concerns\Billable;
use EscolaLms\Payments\Contracts\Billable as ContractsBillable;

// TODO: make user extendable from core + add all traits
class User extends CoreUser implements ContractsBillable
{
    use Billable;
    use HasCourses;
}
