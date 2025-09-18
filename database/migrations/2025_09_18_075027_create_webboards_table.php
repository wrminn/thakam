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

        Schema::create('threads', function (Blueprint $table) {
            $table->id('threads_id');
            $table->string('threads_title');
            $table->longText('threads_content')->nullable();
            $table->string('threads_view')->nullable();
            $table->enum('threads_status', ['O', 'P', 'D'])->default('O'); // A=แสดง, D=ลบ
            $table->dateTime('threads_date_insert'); // วันที่ลูกค้าลงข้อมูลจริง
            $table->string('threads_name')->nullable();
            $table->string('threads_email')->nullable();
            $table->string('threads_ip')->nullable();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id('posts_id');
            $table->string('threads_id');
            $table->longText('posts_content');
            $table->dateTime('posts_date_insert');
            $table->string('posts_name');
            $table->string('posts_email');
            $table->string('posts_ip');
           $table->enum('posts_status', ['O', 'P', 'D'])->default('O'); // A=แสดง, D=ลบ
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
        Schema::dropIfExists('posts');
    }
};
