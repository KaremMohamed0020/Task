<?php 

// app/Services/LogoutService.php

namespace App\Services;


class LogoutService
{
    public function logout($user)
    {
        $user->token()->revoke();
    }
}
