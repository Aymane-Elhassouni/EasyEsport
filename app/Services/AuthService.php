<?php

namespace App\Services;

use App\DAO\Interfaces\UserDAOInterface;
use App\Models\Role;
use App\Models\User;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Cookie;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected UserDAOInterface $userDAO
    ) {}

    public function register(array $data): User
    {
        $roleId = Role::where('name', 'player')->value('id')
            ?? Role::value('id')
            ?? 1;

        return $this->userDAO->save([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role_id'   => $roleId,
        ]);
    }

    public function login(array $credentials): ?string
    {
        return Auth::guard('api')->attempt($credentials) ?: null;
    }

    public function logout(): void
    {
        try {
            Auth::guard('api')->logout();
        } catch (\Exception) {
            // Token already invalid or missing — safe to ignore
        }
    }

    public function makeTokenCookie(string $token): Cookie
    {
        $ttl = (int) config('jwt.ttl', 60);

        return cookie(
            name:     'token',
            value:    $token,
            minutes:  $ttl,
            path:     '/',
            domain:   null,
            secure:   app()->isProduction(),
            httpOnly: true,
            raw:      false,
            sameSite: 'Lax',
        );
    }
}
