<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Illuminate\Support\Facades\Log;

class ImagesToWebp extends Command
{
    protected $signature = 'app:images-to-webp
                            {--path= : Custom directory path to scan instead of the defaults}
                            {--quality=80 : WebP quality 1–100}
                            {--delete-originals : Delete original files after successful conversion}';

    protected $description = 'Convert JPG/PNG/GIF images to WebP format in storage/app/public and public/';

    private const SUPPORTED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];

    public function handle(): int
    {
        if (! extension_loaded('gd')) {
            $this->error('The GD PHP extension is not loaded. Please enable it in php.ini.');

            return self::FAILURE;
        }

        if (! function_exists('imagewebp')) {
            $this->error('GD is loaded but WebP support is not compiled in. Recompile PHP with --with-webp.');

            return self::FAILURE;
        }

        ini_set('memory_limit', '-1');

        $quality = (int) $this->option('quality');

        if ($quality < 1 || $quality > 100) {
            $this->error('--quality must be between 1 and 100.');

            return self::FAILURE;
        }

        $deleteOriginals = (bool) $this->option('delete-originals');
        $customPath      = $this->option('path');

        $scanDirs = $customPath
            ? [$customPath]
            : [
                storage_path('app/public'),
                public_path(),
            ];

        $files = $this->collectImageFiles($scanDirs);

        if (empty($files)) {
            $this->info('No convertible images found.');

            return self::SUCCESS;
        }

        $this->info(sprintf('Found %d image(s). Converting at quality %d…', count($files), $quality));

        $bar = $this->output->createProgressBar(count($files));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->setMessage('Starting…');
        $bar->start();

        $converted = 0;
        $skipped   = 0;
        $failed    = 0;

        foreach ($files as $filePath) {
            $webpPath = $this->webpPath($filePath);

            if (file_exists($webpPath)) {
                $bar->setMessage('Skipped: ' . basename($filePath));
                $bar->advance();
                $skipped++;
                continue;
            }

            $bar->setMessage(basename($filePath));

            $success = $this->convertToWebp($filePath, $webpPath, $quality);

            if ($success) {
                $converted++;

                if ($deleteOriginals) {
                    @unlink($filePath);
                }
            } else {
                $failed++;
            }

            Log::info('Converted image: ' . $filePath);

            $bar->advance();
        }

        $bar->setMessage('Done.');
        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Status', 'Count'],
            [
                ['<info>Converted</info>', $converted],
                ['<comment>Skipped (webp exists)</comment>', $skipped],
                ['<error>Failed</error>', $failed],
            ]
        );

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Collect all supported image file paths from the given directories.
     *
     * @param  string[]  $dirs
     * @return string[]
     */
    private function collectImageFiles(array $dirs): array
    {
        $files = [];

        foreach ($dirs as $dir) {
            if (! is_dir($dir)) {
                $this->warn("Directory not found, skipping: {$dir}");
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            /** @var SplFileInfo $file */
            foreach ($iterator as $file) {
                if (! $file->isFile()) {
                    continue;
                }

                if (in_array(strtolower($file->getExtension()), self::SUPPORTED_EXTENSIONS, true)) {
                    $files[] = $file->getRealPath();
                }
            }
        }

        return array_unique($files);
    }

    /**
     * Return the .webp sibling path for a given image path.
     */
    private function webpPath(string $filePath): string
    {
        $dir      = dirname($filePath);
        $stem     = pathinfo($filePath, PATHINFO_FILENAME);

        return $dir . DIRECTORY_SEPARATOR . $stem . '.webp';
    }

    /**
     * Convert a single image file to WebP.
     */
    private function convertToWebp(string $source, string $destination, int $quality): bool
    {
        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg'], true) && $this->isCmykJpeg($source)) {
            $this->newLine();
            $this->warn("Skipped CMYK JPEG (GD unsupported): {$source}");

            return false;
        }

        $image = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($source),
            'png'         => $this->loadPng($source),
            'gif'         => @imagecreatefromgif($source),
            'jfif'        => @imagecreatefromjpeg($source),
            default       => false,
        };

        if ($image === false) {
            $this->newLine();
            $this->warn("Could not load image: {$source}");

            return false;
        }

        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        $result = @imagewebp($image, $destination, $quality);
        imagedestroy($image);
        gc_collect_cycles();

        if (! $result) {
            $this->newLine();
            $this->warn("Could not write WebP: {$destination}");
        }

        return $result;
    }

    /**
     * Detect CMYK JPEG files by reading the SOF marker from the raw file header.
     * GD only supports RGB/YCbCr JPEGs; CMYK ones return false silently.
     */
    private function isCmykJpeg(string $source): bool
    {
        $handle = @fopen($source, 'rb');

        if ($handle === false) {
            return false;
        }

        $isCmyk = false;
        $data   = fread($handle, 65536);
        fclose($handle);

        $len = strlen($data);

        for ($i = 0; $i < $len - 1; $i++) {
            if (ord($data[$i]) !== 0xFF) {
                continue;
            }

            $marker = ord($data[$i + 1]);

            if ($marker >= 0xC0 && $marker <= 0xC3) {
                if ($i + 9 < $len && ord($data[$i + 9]) === 4) {
                    $isCmyk = true;
                }
                break;
            }
        }

        return $isCmyk;
    }

    /**
     * Load a PNG image with alpha channel preserved.
     *
     * @return \GdImage|false
     */
    private function loadPng(string $source): mixed
    {
        $image = @imagecreatefrompng($source);

        if ($image === false) {
            return false;
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        return $image;
    }
}
