<?php

namespace App\Exports;

use App\Enum\GenderType;
use EscolaLms\Core\Enum\UserRole;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    private Collection $users;

    /**
     * UsersExport constructor.
     * @param Collection $users
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users
            ->map(fn (Authenticatable $user) => [
                $user->getKey(),
                $user->first_name,
                $user->last_name,
                $user->age,
                GenderType::getName($user->gender),
                $user->country,
                $user->city,
                $user->street,
                $user->postcode,
                $user->email,
                $this->getRoles($user),
                $user->is_active ? 'Active' : 'Inactive',
                $user->onboarding_completed ? 'Completed' : 'Waiting',
                $user->created_at,
                $user->updated_at
            ]);
    }

    public function headings(): array
    {
        return [
            'Sl.no',
            'First Name',
            'Last Name',
            'Age',
            'Gender',
            'Country',
            'City',
            'Street',
            'Postcode',
            'Email ID',
            'Roles',
            'Status',
            'Onboarding',
            'Created at',
            'Updated at'
        ];
    }

    private function getRoles(Authenticatable $user): string
    {
        $roles = [];

        foreach (UserRole::asArray() as $role) {
            if ($user->hasRole($role)) {
                $roles[] = ucfirst($role);
            }
        }

        return implode(', ', $roles);
    }
}
