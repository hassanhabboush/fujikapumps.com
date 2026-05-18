<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDeploy extends Command
{
    protected $signature = 'app:deploy';

    protected $description = 'Clear cache, fix storage permissions, seed DB, then re-optimize';

    public function handle(): int
    {
        $this->info('Step 1/4 — optimize:clear');
        $this->call('optimize:clear');

        $this->info('Step 2/4 — chmod -R 777 storage/');
        $storagePath = base_path('storage');
        exec("chmod -R 777 {$storagePath}", $output, $exitCode);
        if ($exitCode !== 0) {
            $this->warn('chmod failed (expected on Windows). Skipping.');
        } else {
            $this->line('  <info>Done.</info>');
        }

        $this->info('Step 3/4 — db:seed');
        $this->call('db:seed');

        $this->info('Step 4/4 — optimize');
        $this->call('optimize');

        // Clear cache
        $this->info('Step 5/5 — storage:link');
        $this->call('storage:link');

        $this->newLine();
        $this->info('All steps completed successfully.');

        return self::SUCCESS;
    }
}
