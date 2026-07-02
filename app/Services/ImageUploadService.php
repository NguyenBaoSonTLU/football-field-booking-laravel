<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function storeFieldImage(UploadedFile $file): string
    {
        return $file->store('fields', 'public');
    }

    public function storeAvatar(UploadedFile $file): string
    {
        return $file->store('avatars', 'public');
    }

    public function delete(?string $path): void
    {
        if ($path && ! str_starts_with($path, '/images/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
