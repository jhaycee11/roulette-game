<?php

namespace App\Http\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use App\Services\StaticAuthService;
use App\Models\StaticUser;

class StaticGuard implements Guard
{
    use GuardHelpers;

    protected $request;
    protected $provider;
    protected $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        // Check session for logged in user
        $username = $this->request->session()->get('static_auth_user');
        if ($username) {
            $userData = StaticAuthService::getUser($username);
            if ($userData) {
                $user = new StaticUser($userData);
            }
        }

        return $this->user = $user;
    }

    public function validate(array $credentials = [])
    {
        if (empty($credentials['username']) || empty($credentials['password'])) {
            return false;
        }

        $user = StaticAuthService::authenticate($credentials['username'], $credentials['password']);
        return $user !== null;
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        $user = StaticAuthService::authenticate($credentials['username'] ?? '', $credentials['password'] ?? '');
        
        if ($user) {
            $this->login(new StaticUser($user), $remember);
            return true;
        }

        return false;
    }

    public function login($user, $remember = false)
    {
        $this->user = $user;
        $this->request->session()->put('static_auth_user', $user->username);
        $this->request->session()->regenerate();
    }

    public function logout()
    {
        $this->user = null;
        $this->request->session()->forget('static_auth_user');
        $this->request->session()->invalidate();
        $this->request->session()->regenerateToken();
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}
