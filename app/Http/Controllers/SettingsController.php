<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ProfileServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct(
        protected ProfileServiceInterface $profileService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $user->loadMissing('role');

        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return view('dashboard.settings', ['data' => null]);
        }

        try {
            $data = $this->profileService->getFullProfileData(Auth::id());
        } catch (ModelNotFoundException) {
            abort(404, 'Profile not found.');
        }

        return view('dashboard.settings', compact('data'));
    }
}
