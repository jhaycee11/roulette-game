<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\StaticAuthService;

class StaticUser implements Authenticatable
{
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['username'];
    }

    public function getAuthPassword()
    {
        return null; // We handle password verification in the service
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // Not implemented for static auth
    }

    public function getRememberTokenName()
    {
        return null;
    }

    public function getKey()
    {
        return $this->attributes['username'];
    }

    public function getKeyName()
    {
        return 'username';
    }

    public function getTable()
    {
        return 'static_users';
    }

    public function getFillable()
    {
        return ['name', 'username'];
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->attributes, $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}
