<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageHelper
{
    public static function upload($file, $folder = 'uploads')
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Create image manager
        $manager = ImageManager::usingDriver(Driver::class);

        // Use wide hero crop for hero, 4:3 for intro, square for others
        $isHeroImage = $folder === 'homepage';
        $isIntroImage = $folder === 'intro';
        $targetWidth = $isHeroImage ? 1920 : ($isIntroImage ? 1200 : 1200);
        $targetHeight = $isHeroImage ? 800 : ($isIntroImage ? 900 : 1200);

        // Decode incoming image
        $image = $manager->decode($file);

        // Resize to fit within target dimensions while preserving aspect ratio.
        // This avoids hard-cropping important content from user-supplied images.
        $image->resize($targetWidth, $targetHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Convert to webp (better compression)
        $filename = uniqid() . '.webp';

        // Encode
        $encoded = $image->encodeUsingFileExtension('webp', quality: 80);

        // Store
        Storage::disk('public')->put("$folder/$filename", $encoded);

        return "$folder/$filename";
    }
}