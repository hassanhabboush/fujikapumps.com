<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->text('photo');
            $table->text('name');
            $table->text('link');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
