<?php

namespace App\Console\Commands;

use App\Models\Family;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

/**
 * One-off repair for the families whose background was written by the old
 * FamilyController, which stored on the public disk (storage/app/public) while
 * every other entity moves uploads into public/<dir>. Those rows only render
 * through the public/storage symlink, so any host without it served a broken
 * image the moment a family was updated.
 *
 * Safe to re-run: rows already on public/ are left alone.
 */
class NormalizeFamilyMedia extends Command
{
    protected $signature = 'app:normalize-family-media {--dry-run : Report what would move without touching anything}';

    protected $description = 'Move family backgrounds off the public disk into public/categorybackground and rewrite their paths';

    private const BACKGROUND_DIR = 'categorybackground';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $families = Family::query()
            ->whereNotNull('background')
            ->where('background', '<>', '')
            ->where('background', 'not like', 'public/%')
            ->get();

        if ($families->isEmpty()) {
            $this->info('Nothing to normalize — every family background already lives under public/.');

            return self::SUCCESS;
        }

        File::ensureDirectoryExists(public_path(self::BACKGROUND_DIR));

        $moved = 0;
        $missing = 0;

        foreach ($families as $family) {
            $stored = $family->getRawOriginal('background');
            $source = storage_path('app/public/' . $stored);
            $name = $this->availableName(basename($stored));
            $target = public_path(self::BACKGROUND_DIR . '/' . $name);

            if (! File::exists($source)) {
                $this->warn(sprintf('#%d %s — file missing at %s, path left as-is.', $family->id, $family->english_name, $source));
                $missing++;

                continue;
            }

            $this->line(sprintf('#%d %s: %s → %s', $family->id, $family->english_name, $stored, self::BACKGROUND_DIR . '/' . $name));

            if (! $dryRun) {
                File::move($source, $target);
                $this->moveWebpSibling($source, $target);

                $family->update([
                    'background' => 'public/' . self::BACKGROUND_DIR . '/' . $name,
                ]);
            }

            $moved++;
        }

        if (! $dryRun && $moved > 0) {
            // The grid feed is a plain key and pivot-free writes never touch it.
            Cache::forget('families');
            Cache::forget('headerCategories');
        }

        $this->newLine();
        $this->info(sprintf(
            '%s %d background(s)%s.',
            $dryRun ? 'Would move' : 'Moved',
            $moved,
            $missing > 0 ? sprintf(', skipped %d with no file on disk', $missing) : ''
        ));

        return self::SUCCESS;
    }

    /**
     * The .webp sibling is what HasMediaUrls actually serves when it exists, so
     * leaving it behind would move the row onto an image nobody sees.
     */
    private function moveWebpSibling(string $source, string $target): void
    {
        $sourceWebp = preg_replace('/\.[^.]+$/', '.webp', $source);
        $targetWebp = preg_replace('/\.[^.]+$/', '.webp', $target);

        if ($sourceWebp !== $source && File::exists($sourceWebp) && ! File::exists($targetWebp)) {
            File::move($sourceWebp, $targetWebp);
        }
    }

    /**
     * public/categorybackground is shared with Category, so a same-named file
     * may already be there — never overwrite it.
     */
    private function availableName(string $name): string
    {
        if (! File::exists(public_path(self::BACKGROUND_DIR . '/' . $name))) {
            return $name;
        }

        $stem = pathinfo($name, PATHINFO_FILENAME);
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $candidate = $stem . '-' . uniqid() . ($extension !== '' ? '.' . $extension : '');

        return $candidate;
    }
}
