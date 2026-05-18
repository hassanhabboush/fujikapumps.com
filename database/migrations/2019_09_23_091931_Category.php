<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Category extends Migration
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
            $table->string('arabic_name');
            $table->string('kurdish_name');
            $table->string('urdu_name');
            $table->string('hebrew_name');
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
}
