<?php

namespace App\Extensions;

use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;

class AccessTokenGuard implements Guard
{
    use GuardHelpers;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function check()
    {
        $token = $this->request->header('Authorization');
        $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));

        if (empty($decoded)) {
            return false;
        }

        $token = Passport::token()->where('id', $decoded->jti)->first();
        if (!$token) {
            return false;
        }

        $this->user = $token->user()->get();

        return true;
    }

    public function user()
    {
        return $this->user;
    }

    /**
     * Validate a user's credentials.
     *
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return false;
    }
}
