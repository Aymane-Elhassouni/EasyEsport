<?php

namespace App\Services;

use App\DAO\Interfaces\ProfileDAOInterface;
use App\DTO\ProfileViewData;
use App\Models\ProfilePlayer;
use App\Models\User;
use App\Presenters\ProfilePresenter;
use App\Services\Interfaces\ProfileServiceInterface;
use App\Services\Interfaces\TeamServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    public function __construct(
        protected ProfileDAOInterface $profileDAO,
        protected TeamServiceInterface $teamService,
        protected ProfilePresenter $presenter,
    ) {}

    public function getAuthProfile(int $userId): ProfilePlayer
    {
        $profile = $this->profileDAO->findByUser($userId);

        if (!$profile) {
            throw new ModelNotFoundException("Profile not found for user {$userId}.");
        }

        return $profile;
    }

    public function updateProfile(int $userId, array $data): ProfilePlayer
    {
        $profile = $this->getAuthProfile($userId);
        
        $profile->update(['bio' => $data['bio'] ?? null]);
        
        return $profile;
    }

    public function updateAccount(int $userId, array $data, ?\Illuminate\Http\UploadedFile $logo = null): User
    {
        $user = User::findOrFail($userId);

        if ($logo) {
            if ($user->logo) {
                Storage::disk('s3')->delete($user->logo);
                Storage::disk('public')->delete($user->logo);
            }
            $path = Storage::disk('s3')->putFile('avatars', $logo, 'public');
            $data['logo'] = $path;
        }

        $user->update(array_filter([
            'firstname' => $data['firstname'] ?? null,
            'lastname'  => $data['lastname']  ?? null,
            'email'     => $data['email']     ?? null,
            'logo'      => $data['logo']      ?? null,
        ]));

        if (isset($data['bio'])) {
            $this->profileDAO->update($userId, ['bio' => $data['bio']]);
        }

        return $user->fresh();
    }

    public function updatePassword(int $userId, string $newPassword): void
    {
        User::findOrFail($userId)->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function getFullProfileData(int $userId): ProfileViewData
    {
        $user    = User::with(['role', 'profile', 'playerGameProfiles'])->findOrFail($userId);
        $profile = $user->profile ?? throw new ModelNotFoundException("Profile not found for user {$userId}.");
        $team    = $this->teamService->getCurrentTeamForUser($userId);

        return $this->presenter->present($user, $profile, $team);
    }
}
