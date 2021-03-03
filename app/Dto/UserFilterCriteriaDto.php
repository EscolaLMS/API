<?php

namespace App\Dto;

use App\Repositories\Criteria\PeriodCriterion;
use App\Repositories\Criteria\Primitives\DoesntHasCriterion;
use App\Repositories\Criteria\Primitives\EqualCriterion;
use App\Repositories\Criteria\Primitives\HasCriterion;
use App\Repositories\Criteria\RoleCriterion;
use App\Repositories\Criteria\UserSearchCriterion;
use Carbon\Carbon;
use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use EscolaSoft\EscolaLms\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserFilterCriteriaDto extends CriteriaDto implements DtoContract, InstantiateFromRequest
{
    public static function instantiateFromRequest(Request $request): InstantiateFromRequest
    {
        $criteria = new Collection();

        if ($request->get('search')) {
            $criteria->push(new UserSearchCriterion($request->get('search')));
        }

        if (!is_null($request->get('role'))) {
            $criteria->push(new RoleCriterion($request->get('role')));
        }

        if (!is_null($request->get('status'))) {
            $criteria->push(new EqualCriterion('is_active', $request->get('status')));
        }

        if (!is_null($request->get('onboarding'))) {
            $criteria->push(
                $request->get('onboarding') ? new HasCriterion('interests') : new DoesntHasCriterion('interests')
            );
        }

        if ($request->get('from') || $request->get('to')) {
            $criteria->push(new PeriodCriterion(new Carbon($request->get('from') ?? 0), new Carbon($request->get('to') ?? null)));
        }

        return new self($criteria);
    }
}
