<?php

namespace App\Services;

class StaticAuthService
{
    /**
     * Static user credentials
     */
    private static $users = [
        'jhaycee' => [
            'name' => 'Jhaycee',
            'password' => 'Strong!Passw0rd'
        ],
        'dessa' => [
            'name' => 'Dessa',
            'password' => 'Strong!Passw0rd'
        ]
    ];

    /**
     * Authenticate user with static credentials
     */
    public static function authenticate(string $username, string $password): ?array
    {
        if (isset(self::$users[$username]) && self::$users[$username]['password'] === $password) {
            return [
                'id' => $username,
                'name' => self::$users[$username]['name'],
                'username' => $username
            ];
        }

        return null;
    }

    /**
     * Get user by username
     */
    public static function getUser(string $username): ?array
    {
        if (isset(self::$users[$username])) {
            return [
                'id' => $username,
                'name' => self::$users[$username]['name'],
                'username' => $username
            ];
        }

        return null;
    }

    /**
     * Get all available users
     */
    public static function getAllUsers(): array
    {
        $users = [];
        foreach (self::$users as $username => $data) {
            $users[] = [
                'id' => $username,
                'name' => $data['name'],
                'username' => $username
            ];
        }
        return $users;
    }
}
