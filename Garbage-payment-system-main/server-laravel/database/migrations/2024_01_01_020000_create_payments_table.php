<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('bill_code', 64)->nullable();
            $table->string('address', 256)->nullable();
            $table->integer('amount');
            $table->string('status', 20)->default('unpaid');
            $table->timestampTz('paid_at')->nullable();
            $table->integer('due_month')->nullable();
            $table->integer('due_year')->nullable();
            $table->timestampsTz();

            $table->index('user_id');
            $table->index(['due_year', 'due_month']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
