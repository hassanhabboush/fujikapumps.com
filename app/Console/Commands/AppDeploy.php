<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDeploy extends Command
{
    protected $signature = 'app:deploy';

    protected $description = 'Clear cache, fix storage permissions, seed DB, then re-optimize';

    public function handle(): int
    {
        $this->info('Step 1/6 — optimize:clear');
        $this->call('optimize:clear');

        $this->info('Step 2/6 — chmod -R 777 storage/');
        $storagePath = base_path('storage');
        exec("chmod -R 777 {$storagePath}", $output, $exitCode);
        if ($exitCode !== 0) {
            $this->warn('chmod failed (expected on Windows). Skipping.');
        } else {
            $this->line('  <info>Done.</info>');
        }

        // database migration
        $this->info('Step 3/6 — migrate');
        $this->call('migrate');

        $this->info('Step 4/6 — db:seed');
        $this->call('db:seed');

        $this->info('Step 5/6 — optimize');
        $this->call('optimize');

        // Clear cache
        $this->info('Step 6/6 — storage:link');
        $this->call('storage:link');

        $this->newLine();
        $this->info('All steps completed successfully.');

        return self::SUCCESS;
    }
}
