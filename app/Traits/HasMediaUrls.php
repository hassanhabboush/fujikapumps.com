<?php

namespace App\Traits;

trait HasMediaUrls
{
    /**
     * Columns whose values should be resolved through url() on read.
     * Override this in any model to customise the list.
     *
     * @var array<int, string>
     */
    protected array $mediaFields = ['background', 'photo', 'image', 'path'];

    /**
     * Intercept attribute reads and wrap media paths with url().
     * The underlying $attributes array is never modified, so fill/save
     * operations always work with the original stored value.
     */
    public function getAttribute($key): mixed
    {
        $value = parent::getAttribute($key);

        if (
            in_array($key, $this->mediaFields, true)
            && is_string($value)
            && $value !== ''
            && !str_starts_with($value, 'http')
        ) {
            $value = str($value)->startsWith('public/') ? str_replace('public/', '', $value) : 'storage/' . $value;
            return asset($value);
        }

        return $value;
    }
}
