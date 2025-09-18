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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id('complaints_id');
            $table->string('complaints_name')->nullable();
            $table->string('complaints_email')->nullable();
            $table->string('complaints_age')->nullable();
            $table->string('complaints_tel')->nullable();
            $table->string('complaints_address')->nullable();
            $table->string('complaints_gender')->nullable();
            $table->string('complaints_topic');
            $table->longText('complaints_detail')->nullable();
            $table->string('complaints_file')->nullable();
            $table->enum('complaints_status', ['N', 'R'])->default('N');
            $table->string('complaints_note')->nullable();
            $table->dateTime('complaints_date_insert');
            $table->dateTime('complaints_date_update')->nullable();
            $table->string('complaints_menu')->nullable();
            $table->string('complaints_ip')->nullable();
            $table->enum('complaints_display', ['A', 'D'])->default('A');
            $table->ipAddress('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
