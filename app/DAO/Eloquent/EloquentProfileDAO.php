<?php

namespace App\DAO\Eloquent;

use App\DAO\Interfaces\ProfileDAOInterface;
use App\Models\ProfilePlayer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentProfileDAO implements ProfileDAOInterface
{
    public function findByUser(int $userId): ?ProfilePlayer
    {
        return ProfilePlayer::where('user_id', $userId)->first();
    }

    public function create(int $userId, array $data): ProfilePlayer
    {
        if (ProfilePlayer::where('user_id', $userId)->exists()) {
            throw new \RuntimeException("A profile already exists for user {$userId}.");
        }

        return ProfilePlayer::create(array_merge($data, ['user_id' => $userId]));
    }

    public function update(int $userId, array $data): ProfilePlayer
    {
        $profile = ProfilePlayer::where('user_id', $userId)->first();

        if (!$profile) {
            throw new ModelNotFoundException("No profile found for user {$userId}.");
        }

        $profile->update($data);

        return $profile;
    }
}
