<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Accepts only a path that this admin already stored under public/<dir>/
 * via the instant-upload endpoint (uuid + known image extension).
 */
class ExistingPublicUpload implements ValidationRule
{
    public function __construct(private readonly string $directory)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        $prefix = 'public/' . $this->directory . '/';

        if (! str_starts_with($value, $prefix)) {
            $fail('The uploaded image is invalid.');

            return;
        }

        $basename = basename($value);

        if ($basename === '' || $basename !== substr($value, strlen($prefix))) {
            $fail('The uploaded image is invalid.');

            return;
        }

        $stem = pathinfo($basename, PATHINFO_FILENAME);
        $ext  = strtolower((string) pathinfo($basename, PATHINFO_EXTENSION));

        if (! Str::isUuid($stem) || ! in_array($ext, ['webp', 'jpg', 'jpeg', 'png'], true)) {
            $fail('The uploaded image is invalid.');

            return;
        }

        if (! File::isFile(public_path($this->directory . '/' . $basename))) {
            $fail('The uploaded image could not be found. Please choose it again.');
        }
    }
}
