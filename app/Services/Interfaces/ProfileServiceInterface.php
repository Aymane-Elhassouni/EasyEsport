<?php

namespace App\Services\Interfaces;

use App\DTO\ProfileViewData;
use App\Models\ProfilePlayer;
use App\Models\User;
use Illuminate\Http\UploadedFile;

interface ProfileServiceInterface
{
    public function getAuthProfile(int $userId): ProfilePlayer;

    public function getFullProfileData(int $userId): ProfileViewData;

    public function updateProfile(int $userId, array $data): ProfilePlayer;

    public function updateAccount(int $userId, array $data, ?UploadedFile $logo = null): User;

    public function updatePassword(int $userId, string $newPassword): void;
}
