<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Product::categories() and Product::subCategories() have always pointed at
 * product_category / product_subcategory, but neither table was ever created
 * by a migration — they only exist where they were added to the database by
 * hand. Guarded with hasTable so this is a no-op on those databases.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_category')) {
            Schema::create('product_category', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('category_id');

                $table->index('product_id');
                $table->index('category_id');
            });
        }

        if (! Schema::hasTable('product_subcategory')) {
            Schema::create('product_subcategory', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('sub_category_id');

                $table->index('product_id');
                $table->index('sub_category_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('product_subcategory');
    }
};
