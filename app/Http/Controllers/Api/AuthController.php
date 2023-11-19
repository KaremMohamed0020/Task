<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\LoginService;
use App\Services\LogoutService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $loginService;
    protected $logoutService;

    public function __construct(LoginService $loginService, LogoutService $logoutService)
    {
        $this->loginService = $loginService;
        $this->logoutService = $logoutService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = $this->loginService->login($credentials);

        return response()->json(['token' => $token], 200);
    }

    public function logout()
    {
        $this->logoutService->logout(Auth::user());

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function users()
    {
        $user = User::find(1); // Replace $userId with the actual user ID

        return $roles = $user->getRoleNames();
    }
}
