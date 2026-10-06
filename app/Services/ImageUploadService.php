<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageUploadService
{
    public function upload(UploadedFile $file, string $directory, int $maxWidth = 800, int $maxHeight = 800): string
    {
        $directory = trim($directory, '/');
        $targetDirectory = public_path('uploads/'.$directory);

        if (! is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $filename = Str::uuid().'.webp';
        $relativePath = 'uploads/'.$directory.'/'.$filename;

        Image::read($file->getRealPath())
            ->scaleDown(width: $maxWidth, height: $maxHeight)
            ->toWebp(quality: 88)
            ->save(public_path($relativePath));

        return $relativePath;
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path(ltrim($path, '/'));

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
