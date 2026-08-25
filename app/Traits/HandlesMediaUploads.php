<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Shared upload handling for the admin controllers.
 *
 * Files still land in public/<dir>/ rather than the public disk, so the
 * legacy rows and the .webp siblings HasMediaUrls looks for keep resolving
 * from a single directory per entity.
 *
 * Every upload is resized (longest side) and stored as WebP so a phone
 * photo cannot hang the public site the way unoptimized PNG/JPEG did.
 */
trait HandlesMediaUploads
{
    private const DEFAULT_MAX_SIDE = 1600;

    private const WEBP_QUALITY = 80;

    /**
     * Optimize an upload into public/<dir>/ and return the path to store.
     *
     * Named with a uuid rather than time() . getClientOriginalName(), which
     * collided under concurrent uploads and preserved user-supplied names.
     */
    protected function storeMedia(UploadedFile $file, string $dir, int $maxSide = self::DEFAULT_MAX_SIDE): string
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '120');

        File::ensureDirectoryExists(public_path($dir));

        $name = Str::uuid() . '.webp';
        $destination = public_path($dir . '/' . $name);

        if ($this->writeOptimizedWebp($file->getRealPath(), $destination, $maxSide)) {
            return 'public/' . $dir . '/' . $name;
        }

        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($dir), $name);

        return 'public/' . $dir . '/' . $name;
    }

    /**
     * Delete a stored media file and the .webp sibling HasMediaUrls prefers —
     * dropping only the original would orphan the image actually being served.
     */
    protected function deleteMedia(?string $storedPath, string $dir): void
    {
        if (blank($storedPath)) {
            return;
        }

        $fullPath = public_path($dir . '/' . basename($storedPath));
        $webpPath = preg_replace('/\.[^.]+$/', '.webp', $fullPath);

        foreach (array_unique([$fullPath, $webpPath]) as $path) {
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }

    /**
     * Resize so the longest side is at most $maxSide, then write WebP.
     * Returns false if GD cannot load or write the file (caller stores original).
     */
    private function writeOptimizedWebp(string $source, string $destination, int $maxSide): bool
    {
        if (! function_exists('imagewebp') || $source === '' || ! is_file($source)) {
            return false;
        }

        // GD keeps the full bitmap in RAM (width × height × 4). A 5 MB JPEG
        // can still be 8000px wide and kill the PHP-FPM worker (Cloudflare 502).
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '120');

        $info = @getimagesize($source);

        if ($info === false) {
            return false;
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG  => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => @imagecreatefromwebp($source),
            IMAGETYPE_GIF  => @imagecreatefromgif($source),
            default        => false,
        };

        if ($image === false) {
            return false;
        }

        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min(1, $maxSide / max($width, $height, 1));

        if ($scale < 1) {
            $newWidth = max(1, (int) round($width * $scale));
            $newHeight = max(1, (int) round($height * $scale));
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $ok = @imagewebp($image, $destination, self::WEBP_QUALITY);
        imagedestroy($image);

        return $ok && is_file($destination) && filesize($destination) > 0;
    }
}
