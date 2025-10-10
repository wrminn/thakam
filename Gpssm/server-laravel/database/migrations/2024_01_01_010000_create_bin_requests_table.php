<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bin_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('date');
            $table->text('subject');
            $table->string('prefix')->nullable();
            $table->string('full_name');
            $table->integer('age')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('house_no');
            $table->string('moo')->nullable();
            $table->string('road')->nullable();
            $table->string('subdistrict');
            $table->string('district');
            $table->string('province');
            $table->string('postcode');
            $table->string('place_type');
            $table->string('place_type_other')->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->text('detail')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('consent')->default(false);
            $table->timestampsTz();

            $table->index('created_at');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bin_requests');
    }
};
