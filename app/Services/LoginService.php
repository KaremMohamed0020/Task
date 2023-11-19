<?php 

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function login(array $credentials): string
    {
        if (Auth::attempt($credentials)) {
            return auth()->user()->createToken('MyApp')->accessToken;
        }

        abort(401, 'Unauthorized');
    }
}
