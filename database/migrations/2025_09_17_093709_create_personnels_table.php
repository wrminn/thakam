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
        Schema::create('personnel', function (Blueprint $table) {
            $table->id('personnel_id');
            $table->string('personnel_name')->nullable();
            $table->string('personnel_position');
            $table->string('personnel_tel');
            $table->bigInteger('personnel_seq')->nullable();
            $table->bigInteger('personnel_parent')->nullable();
            $table->string('personnel_path')->nullable();
            $table->dateTime('personnel_date_insert'); // วันที่ลูกค้าลงข้อมูลจริง
            $table->dateTime('personnel_date_update')->nullable(); // วันที่อัปเดต
            $table->string('personnel_menu')->nullable(); // เป็นข้อมูลของเมนูไหน
            $table->unsignedBigInteger('personnel_user_id_insert')->nullable(); // user ที่ insert
            $table->unsignedBigInteger('personnel_user_id_update')->nullable(); // user ที่ update
            $table->enum('personnel_display', ['A', 'D'])->default('A'); // A=แสดง, D=ลบ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
