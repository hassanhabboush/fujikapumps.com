<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\File;

/**
 * The admin controllers move uploads into public/<dir>/ rather than a fake
 * disk, so tests have to sweep up what they create — without touching the real
 * catalog images that already live there.
 */
trait CleansUploadDirectory
{
    /** @var array<int, string> */
    private array $uploadFilesBefore = [];

    /**
     * Guards the sweep. If setUp aborts before the snapshot is taken, an
     * "everything is new" diff would cover the whole directory and delete real
     * catalog images, so cleanup only runs once this is true.
     */
    private bool $uploadSnapshotTaken = false;

    /** The public/ subdirectory the controller under test writes into. */
    abstract protected function uploadDirectory(): string;

    protected function snapshotUploadDirectory(): void
    {
        File::ensureDirectoryExists(public_path($this->uploadDirectory()));
        $this->uploadFilesBefore = $this->uploadDirectoryFiles();
        $this->uploadSnapshotTaken = true;
    }

    protected function cleanUploadDirectory(): void
    {
        if (! $this->uploadSnapshotTaken) {
            return;
        }

        foreach (array_diff($this->uploadDirectoryFiles(), $this->uploadFilesBefore) as $path) {
            File::delete($path);
        }
    }

    /** @return array<int, string> */
    private function uploadDirectoryFiles(): array
    {
        return array_map(
            fn ($file) => $file->getPathname(),
            File::files(public_path($this->uploadDirectory()))
        );
    }
}
