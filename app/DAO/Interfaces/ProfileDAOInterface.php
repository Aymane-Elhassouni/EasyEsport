<?php

namespace App\DAO\Interfaces;

use App\Models\ProfilePlayer;

interface ProfileDAOInterface
{
    public function findByUser(int $userId): ?ProfilePlayer;

    public function create(int $userId, array $data): ProfilePlayer;

    public function update(int $userId, array $data): ProfilePlayer;
}
