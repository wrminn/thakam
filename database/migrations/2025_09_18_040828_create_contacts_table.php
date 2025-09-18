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
        
        Schema::create('contacts', function (Blueprint $table) {
            $table->id('contacts_id');
            $table->string('contacts_topic');
            $table->string('contacts_name')->nullable();
            $table->string('contacts_tel')->nullable();
            $table->string('contacts_address')->nullable();
            $table->string('contacts_email')->nullable();
            $table->longText('contacts_detail')->nullable();
            $table->enum('contacts_status', ['N', 'R'])->default('N');
            $table->string('contacts_note')->nullable();
            $table->dateTime('contacts_date_insert');
            $table->dateTime('contacts_date_update')->nullable();
            $table->string('contacts_menu')->nullable();
            $table->string('contacts_ip')->nullable();
            $table->enum('contacts_display', ['A', 'D'])->default('A');
            $table->ipAddress('ip_address')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
