<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_parameter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('Model')->nullable();
            $table->string('SerialNumber')->nullable();
            $table->string('PowerKw')->nullable();
            $table->string('PowerHp')->nullable();
            $table->string('q')->nullable();
            $table->string('h')->nullable();
            $table->string('v')->nullable();
            $table->string('Discharge_diameter')->nullable();
            $table->string('Hertz')->nullable();
            $table->string('Material')->nullable();
            $table->string('RPM')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_parameter');
    }
};
