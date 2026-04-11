<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Handle user registration logic.
     */
    public function register(array $data): User;

    /**
     * Handle user authentication and return JWT token.
     */
    public function login(array $credentials): ?string;
}
