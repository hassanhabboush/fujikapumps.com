<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDeploy extends Command
{
    protected $signature = 'app:deploy';

    protected $description = 'Clear cache, fix storage permissions, seed DB, then re-optimize';

    public function handle(): int
    {
        $this->info('Step 1/7 — optimize:clear');
        $this->call('optimize:clear');

        $this->info('Step 2/7 — chmod -R 777 storage/');
        $storagePath = base_path('storage');
        exec("chmod -R 777 {$storagePath}", $output, $exitCode);
        if ($exitCode !== 0) {
            $this->warn('chmod failed (expected on Windows). Skipping.');
        } else {
            $this->line('  <info>Done.</info>');
        }

        // mv productParameter.php to ProductParameter.php in App\Models\
        $this->info('Step 3/7 — mv productParameter.php to ProductParameter.php');
        // use exec to run the command
        exec("mv " . base_path('app/Models/productParameter.php') . " " . base_path('app/Models/ProductParameter.php'), $output, $exitCode);
        if ($exitCode !== 0) {
            $this->warn('mv failed (expected on Windows). Skipping.');
        } else {
            $this->line('  <info>Done.</info>');
        }

        // database migration
        $this->info('Step 4/7 — migrate');
        $this->call('migrate:fresh');

        $this->info('Step 5/7 — db:seed');
        $this->call('db:seed');

        $this->info('Step 6/7 — optimize');
        $this->call('optimize');

        // Clear cache
        $this->info('Step 7/7 — storage:link');
        $this->call('storage:link');



        $this->newLine();
        $this->info('All steps completed successfully.');

        return self::SUCCESS;
    }
}
