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
        Schema::create('gennericforms', function (Blueprint $table) {
            $table->id('gennericforms_id');
            $table->string('gennericforms_name')->nullable();
            $table->string('gennericforms_name_table')->nullable();
            $table->dateTime('gennericforms_date_insert');
            $table->enum('gennericforms_display', ['A', 'D'])->default('A');
            $table->ipAddress('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gennericforms');
    }
};
