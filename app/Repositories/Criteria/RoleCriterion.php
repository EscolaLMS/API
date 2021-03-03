<?php


namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class RoleCriterion extends Criterion
{
    public function __construct(string $role)
    {
        $role = Role::where('name', $role)->firstOrFail();
        parent::__construct(null, $role);
    }

    public function apply(Builder $query): Builder
    {
        return $query->whereHas('roles', fn ($q) => $q->where('role_id', $this->value->getKey()));
    }
}
