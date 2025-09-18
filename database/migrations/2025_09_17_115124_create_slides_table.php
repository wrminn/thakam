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
        Schema::create('slide', function (Blueprint $table) {
            $table->id('slide_id');
            $table->string('slide_title');
            $table->string('slide_path')->nullable();
            $table->string('slide_link')->nullable();
            $table->enum('slide_type', ['L', 'V'])->default('L'); // A=แสดง, D=ลบ
            $table->dateTime('slide_date_insert'); // วันที่ลูกค้าลงข้อมูลจริง
            $table->dateTime('slide_date_update')->nullable(); // วันที่อัปเดต
            $table->string('slide_menu')->nullable(); // เป็นข้อมูลของเมนูไหน
            $table->unsignedBigInteger('slide_user_id_insert')->nullable(); // user ที่ insert
            $table->unsignedBigInteger('slide_user_id_update')->nullable(); // user ที่ update
            $table->enum('slide_display', ['A', 'D'])->default('A'); // A=แสดง, D=ลบ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slide');
    }
};
