<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_subcategory', function (Blueprint $table) {
            $table->id();
            $table->integer('family_id');
            $table->integer('sub_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_subcategory');
    }
};
