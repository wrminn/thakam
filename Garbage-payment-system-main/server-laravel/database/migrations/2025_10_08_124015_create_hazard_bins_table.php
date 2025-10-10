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
        Schema::create('hazard_bins', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SSchema::create('hazard_bins', function (Blueprint $table) {
    $table->id();
    $table->decimal('lat', 10, 7);
    $table->decimal('lng', 10, 7);
    $table->string('note')->nullable();
    $table->timestamps();
});

    }
};
