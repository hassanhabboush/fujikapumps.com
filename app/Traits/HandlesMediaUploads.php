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
 */
trait HandlesMediaUploads
{
    /**
     * Move an upload into public/<dir>/ and return the path to store.
     *
     * Named with a uuid rather than time() . getClientOriginalName(), which
     * collided under concurrent uploads and preserved user-supplied names.
     */
    protected function storeMedia(UploadedFile $file, string $dir): string
    {
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
}
