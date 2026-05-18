<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomesteadSeeder extends Seeder
{
    private array $tables = [];

    public function run(): void
    {
        $this->loadJson();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->truncateTables();
        $this->seedTables();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('HomesteadSeeder finished.');
    }

    private function loadJson(): void
    {
        // I'm move the homestead_1.json from storage/app/public/homestead_1.json to /homestead_1.json
        $path = base_path('homestead_1.json');


        if (! file_exists($path)) {
            throw new \RuntimeException("JSON file not found at: {$path}");
        }


        $json = json_decode(file_get_contents($path), true);

        foreach ($json as $item) {
            if (isset($item['type']) && $item['type'] === 'table') {
                $this->tables[$item['name']] = $item['data'];
            }
        }
    }

    /**
     * Truncate in reverse dependency order (children first).
     */
    private function truncateTables(): void
    {
        $order = [
            'product_parameter',
            'product_gallery',
            'subcategory_subcategory',
            'family_subcategory',
            'category_subcategory',
            'series',
            'products',
            'family',
            'sub_category_1',
            'sub_category',
            'categories',
            'accessories',
            'gallery',
            'team',
            'slider',
            'contact',
            'about',
            'users',
        ];

        foreach ($order as $table) {
            DB::table($table)->truncate();
        }

        $this->command->info('All tables truncated.');
    }

    /**
     * Seed in dependency order (parents first).
     */
    private function seedTables(): void
    {
        $order = [
            'users',
            'about',
            'contact',
            'slider',
            'gallery',
            'team',
            'accessories',
            'categories',
            'sub_category',
            'sub_category_1',
            'family',
            'series',
            'products',
            'product_gallery',
            'product_parameter',
            'category_subcategory',
            'family_subcategory',
            'subcategory_subcategory',
        ];

        foreach ($order as $table) {
            $this->insertTable($table);
        }
    }

    private function insertTable(string $table): void
    {
        if (empty($this->tables[$table])) {
            $this->command->warn("  [SKIP] {$table}: no data found in JSON");
            return;
        }

        $rows  = $this->tables[$table];
        $count = count($rows);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table($table)->insert($chunk);
        }

        $this->command->info("  [OK] {$table}: {$count} row(s)");
    }
}
