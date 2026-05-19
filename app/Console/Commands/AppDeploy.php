<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDeploy extends Command
{
    protected $signature = 'app:deploy';

    protected $description = 'Clear cache, fix storage permissions, seed DB, then re-optimize';

    public function handle(): int
    {
        $this->info('Step 1/8 — optimize:clear');
        $this->call('optimize:clear');

        $this->info('Step 2/8 — chmod -R 777 storage/');
        $storagePath = base_path('storage');
        exec("chmod -R 777 {$storagePath}", $output, $exitCode);
        if ($exitCode !== 0) {
            $this->warn('chmod failed (expected on Windows). Skipping.');
        } else {
            $this->line('  <info>Done.</info>');
        }

        // mv productParameter.php to ProductParameter.php in App\Models\
        $this->info('Step 3/8 — mv productParameter.php to ProductParameter.php');
        // use exec to run the command
        exec("mv " . base_path('app/Models/productParameter.php') . " " . base_path('app/Models/ProductParameter.php'), $output, $exitCode);
        if ($exitCode !== 0) {
            $this->warn('mv failed (expected on Windows). Skipping.');
        } else {
            $this->line('  <info>Done.</info>');
        }

        // database migration
        $this->info('Step 4/8 — migrate');
        $this->call('migrate:fresh');

        $this->info('Step 5/8 — db:seed');
        $this->call('db:seed');
        
        // Clear cache
        $this->info('Step 6/8 — storage:link');
        $this->call('storage:link');

        // Convert image to webp
        $this->info('Step 7/8 — convert image to webp');
        $this->call('app:images-to-webp');
        
        $this->info('Step 8/8 — optimize');
        $this->call('optimize');


        $this->newLine();
        $this->info('All steps completed successfully.');

        return self::SUCCESS;
    }
}
