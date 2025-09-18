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
        Schema::create('corruptions', function (Blueprint $table) {
            $table->id('corruptions_id');
            $table->string('corruptions_name')->nullable();
            $table->string('corruptions_email')->nullable();
            $table->string('corruptions_age')->nullable();
            $table->string('corruptions_tel')->nullable();
            $table->string('corruptions_address')->nullable();
            $table->string('corruptions_gender')->nullable();
            $table->string('corruptions_topic');
            $table->longText('corruptions_detail')->nullable();
            $table->string('corruptions_file')->nullable();
            $table->enum('corruptions_status', ['N', 'R'])->default('N');
            $table->string('corruptions_note')->nullable();
            $table->dateTime('corruptions_date_insert');
            $table->dateTime('corruptions_date_update')->nullable();
            $table->string('corruptions_menu')->nullable();
            $table->string('corruptions_ip')->nullable();
            $table->enum('corruptions_display', ['A', 'D'])->default('A');
            $table->ipAddress('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corruptions');
    }
};
