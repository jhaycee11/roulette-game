<?php

namespace App\Http\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\StaticAuthService;
use App\Models\StaticUser;

class StaticUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        $userData = StaticAuthService::getUser($identifier);
        return $userData ? new StaticUser($userData) : null;
    }

    public function retrieveByToken($identifier, $token)
    {
        // Not implemented for static auth
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Not implemented for static auth
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials['username'])) {
            return null;
        }

        $userData = StaticAuthService::getUser($credentials['username']);
        return $userData ? new StaticUser($userData) : null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return StaticAuthService::authenticate($credentials['username'] ?? '', $credentials['password'] ?? '') !== null;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Not applicable for static authentication
        return false;
    }
}
