<?php

namespace App\Traits;

trait HasMediaUrls
{
    protected array $mediaFields = ['background', 'photo', 'image', 'path'];

    public function getAttribute($key): mixed
    {
        $value = parent::getAttribute($key);

        if (
            in_array($key, $this->mediaFields, true)
            && is_string($value)
            && $value !== ''
            && !str_starts_with($value, 'http')
        ) {
            $relativePath = str($value)->startsWith('public/')
                ? str_replace('public/', '', $value)
                : 'storage/' . $value;

            $webpPath = preg_replace('/\.[^.]+$/', '', $relativePath) . '.webp';

            if (file_exists(public_path($webpPath))) {
                return $this->publicMediaUrl($webpPath);
            }

            if (file_exists(public_path($relativePath))) {
                return $this->publicMediaUrl($relativePath);
            }

            return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
        }

        return $value;
    }

    private function publicMediaUrl(string $relativePath): string
    {
        $encoded = collect(explode('/', $relativePath))
            ->map(fn (string $segment) => rawurlencode($segment))
            ->implode('/');

        return asset($encoded);
    }
}
