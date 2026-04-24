<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        try {
            $user = Auth::user();
            $folder = "players/{$user->id}/avatar";

            // Supprimer l'ancienne image si elle existe
            if ($user->logo) {
                $this->imageService->delete($user->logo);
            }

            $path = $this->imageService->upload($request->file('image'), $folder);

            // Mettre à jour l'utilisateur avec la nouvelle URL
            $user->update(['logo' => $path]);

            return response()->json([
                'success' => true,
                'url' => config('filesystems.disks.s3.url') . '/' . $path,
                'message' => 'Image uploaded successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}