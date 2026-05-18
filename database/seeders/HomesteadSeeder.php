<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Accessory;
use App\Models\Category;
use App\Models\CategorySubcategory;
use App\Models\Contact;
use App\Models\Family;
use App\Models\FamilySubcategory;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductParameter;
use App\Models\Series;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\SubCategory1;
use App\Models\SubcategorySubcategory;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomesteadSeeder extends Seeder
{
    private array $tables = [];

    private array $modelMap = [
        'users'                   => User::class,
        'about'                   => About::class,
        'contact'                 => Contact::class,
        'slider'                  => Slider::class,
        'gallery'                 => Gallery::class,
        'team'                    => Team::class,
        'accessories'             => Accessory::class,
        'categories'              => Category::class,
        'sub_category'            => SubCategory::class,
        'sub_category_1'          => SubCategory1::class,
        'family'                  => Family::class,
        'series'                  => Series::class,
        'products'                => Product::class,
        'product_gallery'         => ProductGallery::class,
        'product_parameter'       => ProductParameter::class,
        'category_subcategory'    => CategorySubcategory::class,
        'family_subcategory'      => FamilySubcategory::class,
        'subcategory_subcategory' => SubcategorySubcategory::class,
    ];

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

        $modelClass = $this->modelMap[$table] ?? null;

        if ($modelClass === null) {
            $this->command->warn("  [SKIP] {$table}: no model mapped");
            return;
        }

        // Resolve actual columns from the live DB schema so that
        // any JSON keys absent from the real table are stripped out.
        $allowed = array_flip(Schema::getColumnListing($table));

        $rows  = $this->tables[$table];
        $count = count($rows);

        $rows = array_map(
            fn($row) => array_intersect_key($row, $allowed),
            $rows
        );

        /** @var \Illuminate\Database\Eloquent\Model $instance */
        $instance = new $modelClass();

        foreach (array_chunk($rows, 500) as $chunk) {
            $instance->newQuery()->insert($chunk);
        }

        $this->command->info("  [OK] {$table}: {$count} row(s)");
    }
}
