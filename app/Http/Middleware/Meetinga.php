<?php

namespace App\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use EscolaLms\Auth\Services\Contracts\UserGroupServiceContract;
use EscolaLms\Auth\Services\Contracts\UserServiceContract;
use EscolaLms\Auth\Dtos\UserSaveDto;
use EscolaLms\Auth\Dtos\UserUpdateSettingsDto;
use Ramsey\Collection\Map\TypedMap;
use Illuminate\Support\Facades\App;

use Closure;

class Meetinga
{
    private function createUserFromResponse($request, $data)
    {
        $userService = App::make(UserServiceContract::class);
        $userGroupService = App::make(UserGroupServiceContract::class);

        $userSaveDto = new UserSaveDto($data['firstname'], $data['lastname'], true, [], $request->get('email'), $request->get('password'), true);

        $map = new TypedMap('string', 'mixed', ['foo'=>'todo', 'todo'=>'bar', 'city' => $data['city']]);
        $userSettingsDto = new UserUpdateSettingsDto($map);
        //** doto  */
        $user = $userService->createWithSettings($userSaveDto, $userSettingsDto);

        // TODO firstOrCreate
        // TODO add settings
        // TODO find or create groups
        // TODO assign to groups

        // TODO check certificate with another call


        dd($user);
    }
    public function handle($request, Closure $next)
    {
        $response  = $next($request);

        if ($response->status() === 422 && $request->has('email') && $request->has('password')) {
            $postResponse = Http::withHeaders([
                'App-Version' => config('meetinga.app-version'),
                'Api-Key' => config('meetinga.api-key')
            ])->post(config('meetinga.url').'/pl/api/auth/login/', [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);

            if ($postResponse->status() === 200) {
                $this->createUserFromResponse($request, $postResponse->json());
            }
        }

        // Perform action

        return $response;
    }
}
