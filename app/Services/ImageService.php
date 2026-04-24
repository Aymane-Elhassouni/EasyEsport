<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    public function upload(UploadedFile $file, string $folder): string
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;

        Storage::disk('s3')->putFileAs($folder, $file, $filename, 'public');

        return $path; // Retourner seulement le chemin relatif
    }

    public function delete(string $path): bool
    {
        if (Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->delete($path);
        }

        return false; // Fichier n'existe pas
    }
}