<?php

namespace App\DAO\Interfaces;

use App\Models\User;

interface UserDAOInterface
{
    /**
     * Create a new user record.
     */
    public function save(array $data): User;

    /**
     * Find a user by their email address.
     */
    public function getByEmail(string $email): ?User;

    /**
     * Find a user by their ID.
     */
    public function getById(int $id): ?User;
}
