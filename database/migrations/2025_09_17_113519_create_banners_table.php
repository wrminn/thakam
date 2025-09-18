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
        Schema::create('banner', function (Blueprint $table) {
            $table->id('banner_id');
            $table->string('banner_title');
            $table->string('banner_topic_picture')->nullable();
            $table->string('banner_link')->nullable();
            $table->dateTime('banner_date_insert'); // วันที่ลูกค้าลงข้อมูลจริง
            $table->dateTime('banner_date_update')->nullable(); // วันที่อัปเดต
            $table->string('banner_menu')->nullable(); // เป็นข้อมูลของเมนูไหน
            $table->unsignedBigInteger('banner_user_id_insert')->nullable(); // user ที่ insert
            $table->unsignedBigInteger('banner_user_id_update')->nullable(); // user ที่ update
            $table->enum('banner_display', ['A', 'D'])->default('A'); // A=แสดง, D=ลบ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
