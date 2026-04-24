<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Symfony\Component\HttpFoundation\Cookie;

interface AuthServiceInterface
{
    public function register(array $data): User;

    public function login(array $credentials): ?string;

    public function logout(): void;

    public function makeTokenCookie(string $token): Cookie;
}
