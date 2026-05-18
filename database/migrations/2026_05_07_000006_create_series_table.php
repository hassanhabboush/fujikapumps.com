<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('english_name', 40);
            $table->text('link');
            $table->text('photo');
            $table->text('text1')->nullable();
            $table->text('text2')->nullable();
            $table->text('text3')->nullable();
            $table->integer('family_id');
            $table->tinyInteger('enabled')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
