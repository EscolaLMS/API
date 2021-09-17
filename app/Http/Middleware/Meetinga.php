<?php

namespace App\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use EscolaLms\Auth\Services\Contracts\UserGroupServiceContract;
use EscolaLms\Auth\Services\Contracts\UserServiceContract;
use EscolaLms\Auth\Dtos\UserSaveDto;
use EscolaLms\Auth\Dtos\UserUpdateSettingsDto;
use EscolaLms\Auth\Models\Group;
use Ramsey\Collection\Map\TypedMap;
use Illuminate\Support\Facades\App;

use Closure;

class Meetinga
{

    private function createOrFindGroups(Model $mainGroup, array $groups): array
    {

        return array_map(
            fn ($group) => Group::firstOrCreate([
                    'name' => $group['name'],
                    'registerable' => true,
                    'parent_id' => $mainGroup->id
                ])
            $groups
        );
    }

    private function getUserCertificate($data): array
    {

        $slug = $data['registered_events'][0]['slug'];

        $postResponse = Http::withHeaders([
            'App-Version' => config('meetinga.app-version'),
            'Api-Key' => config('meetinga.api-key'),
            'Authorization' => "JWT " . $data['token']
        ])->get(config('meetinga.url') . '/' . $slug . '/pl/api/awf/certificate/');

        if ($postResponse->ok()) {
            return $postResponse->json();
        }

        return [];
    }


    private function createUserFromResponse($request, $data): Model
    {

        $userService = App::make(UserServiceContract::class);
        $userGroupService = App::make(UserGroupServiceContract::class);

        $userSaveDto = new UserSaveDto($data['firstname'], $data['lastname'], true, [], $request->get('email'), $request->get('password'), true);

        $userSettings = collect($data)->only(['city', 'postal_code', 'country', 'gender'])->toArray();

        $mainGroup = Group::firstOrCreate([
            'name' => 'Nauczyciele',
            'registerable' => true
        ]);

        $groups = [$mainGroup];

        $groups = array_merge($groups, $this->createOrFindGroups($mainGroup, $data['user_groups1']));
        $groups = array_merge($groups, $this->createOrFindGroups($mainGroup, $data['user_groups2']));
        $groups = array_merge($groups, $this->createOrFindGroups($mainGroup, $data['user_groups3']));

        $cert = $this->getUserCertificate($data);

        $userSettings += $cert;

        $map = new TypedMap('string', 'mixed', $userSettings);
        $userSettingsDto = new UserUpdateSettingsDto($map);

        $user = $userService->createWithSettings($userSaveDto, $userSettingsDto);

        foreach ($groups as $group) {
            $userGroupService->addMember($group, $user);
        }

        return $user;
    }
    public function handle($request, Closure $next)
    {
        $response  = $next($request);

        if ($response->status() === 422 && $request->has('email') && $request->has('password')) {
            $postResponse = Http::withHeaders([
                'App-Version' => config('meetinga.app-version'),
                'Api-Key' => config('meetinga.api-key')
            ])->post(config('meetinga.url') . '/pl/api/auth/login/', [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);

            if ($postResponse->status() === 200) {
                $this->createUserFromResponse($request, $postResponse->json());
                $response  = $next($request);
            }
        }

        // Perform action

        return $response;
    }
}
