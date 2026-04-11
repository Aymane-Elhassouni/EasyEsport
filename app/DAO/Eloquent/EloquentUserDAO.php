<?php

namespace App\DAO\Eloquent;

use App\DAO\Interfaces\UserDAOInterface;
use App\Models\User;

class EloquentUserDAO implements UserDAOInterface
{
    public function save(array $data): User
    {
        return User::create($data);
    }

    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getById(int $id): ?User
    {
        return User::find($id);
    }
}
