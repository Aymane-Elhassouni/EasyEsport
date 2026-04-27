<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\Interfaces\ProfileServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileServiceInterface $profileService
    ) {}

    public function show()
    {
        $user = Auth::user();
        $user->loadMissing('role');

        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return redirect()->route('admin.profile');
        }

        return redirect()->route('player.profile');
    }

    public function showAdmin()
    {
        return view('pages.admin.profile');
    }

    public function showPlayer()
    {
        try {
            $data = $this->profileService->getFullProfileData(Auth::id());
        } catch (ModelNotFoundException) {
            // create profile if not exists
            \App\Models\ProfilePlayer::firstOrCreate(
                ['user_id' => Auth::id()],
                ['total_matches' => 0, 'win_rate' => 0, 'total_trophies' => 0, 'status' => 'active']
            );
            $data = $this->profileService->getFullProfileData(Auth::id());
        }

        return view('pages.player.profile', compact('data'));
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $this->profileService->updateProfile(Auth::id(), $request->validated());
        } catch (ModelNotFoundException) {
            abort(404, 'Profile not found.');
        }

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }

    public function settings()
    {
        try {
            $data = $this->profileService->getFullProfileData(Auth::id());
        } catch (ModelNotFoundException) {
            abort(404, 'Profile not found.');
        }

        return view('settings.index', compact('data'));
    }

    public function updateAccount(UpdateAccountRequest $request)
    {
        try {
            $this->profileService->updateAccount(
                Auth::id(),
                $request->validated(),
                $request->file('logo')
            );
        } catch (ModelNotFoundException) {
            abort(404, 'Profile not found.');
        }

        return redirect()->route('settings')->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $this->profileService->updatePassword(Auth::id(), $request->validated()['password']);

        return redirect()->route('settings')
            ->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
