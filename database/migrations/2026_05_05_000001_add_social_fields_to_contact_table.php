<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact', function (Blueprint $table) {
            $table->string('instagram')->nullable()->after('Linkedin');
            $table->string('whatsapp')->nullable()->after('instagram');
            $table->string('email')->nullable()->after('whatsapp');
            $table->string('phone1')->nullable()->after('email');
            $table->string('phon2')->nullable()->after('phone1');
            $table->string('address')->nullable()->after('phon2');
            $table->string('website')->nullable()->after('address');
            
        });
    }

    public function down(): void
    {
        Schema::table('contact', function (Blueprint $table) {
            $table->dropColumn(['instagram', 'whatsapp', 'email', 'phone1', 'phon2', 'address']);
        });
    }
};
