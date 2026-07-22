<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('english_name');
            $table->string('arabic_name')->nullable();
            $table->string('kurdish_name')->nullable();
            $table->string('urdu_name')->nullable();
            $table->string('hebrew_name')->nullable();
            $table->string('logo');
            $table->string('background')->nullable();
            $table->timestamps();
            $table->string('short_descreption', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
