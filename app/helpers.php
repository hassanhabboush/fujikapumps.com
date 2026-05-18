<?php

if (! function_exists('versioned_asset')) {
    /**
     * Generate a versioned asset URL using the file's last modification time.
     * Appends ?v=<timestamp> so browsers fetch the new file when it changes,
     * while still benefiting from long-lived Cache-Control headers otherwise.
     */
    function versioned_asset(string $path): string
    {
        $fullPath = public_path($path);
        $version  = file_exists($fullPath) ? filemtime($fullPath) : time();

        return asset($path) . '?v=' . $version;
    }
}
