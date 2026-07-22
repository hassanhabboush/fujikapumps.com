<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Anonymous so this file stops declaring a global `Store` class. Composer
 * classmaps database/, and once that class was loaded PHP's case-insensitive
 * class lookup made class_exists('store') true — which breaks any
 * Route::controller(...) group registering a route with the string action
 * 'store' (see the family routes in routes/web.php).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('logo');
            $table->integer('cat_id');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
};
