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
        Schema::create('categories', function (Blueprint $table) {
           $table->id('categories_id');
            $table->string('categories_name');
            $table->dateTime('categories_date_insert'); 
            $table->dateTime('categories_date_update')->nullable(); 
            $table->string('categories_menu')->nullable();
            $table->unsignedBigInteger('categories_user_id_insert')->nullable();
            $table->unsignedBigInteger('categories_user_id_update')->nullable(); 
            $table->enum('categories_display', ['A', 'D'])->default('A'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
