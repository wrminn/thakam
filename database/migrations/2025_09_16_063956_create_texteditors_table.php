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
        Schema::create('texteditor', function (Blueprint $table) {
            $table->id('texteditor_id');
            $table->string('texteditor_title');
            $table->string('texteditor_topic_picture')->nullable();
            $table->date('texteditor_date_show')->nullable(); //วันที่ต้องการให้แสดงหน้าเว็บ
            $table->unsignedBigInteger('texteditor_category_id')->nullable(); // หมวดหมู่
            $table->dateTime('texteditor_date_insert'); // วันที่ลูกค้าลงข้อมูลจริง
            $table->dateTime('texteditor_date_update')->nullable(); 
            $table->string('texteditor_menu')->nullable(); 
            $table->unsignedBigInteger('texteditor_user_id_insert')->nullable(); 
            $table->unsignedBigInteger('texteditor_user_id_update')->nullable(); 
            $table->enum('texteditor_display', ['A', 'D'])->default('A'); // A=แสดง, D=ลบ
        });

        Schema::create('texteditor_detail', function (Blueprint $table) {
            $table->id('texteditor_detail_id');
            $table->string('texteditor_id');
            $table->string('texteditor_detail_seq');
            $table->longText('texteditor_detail');
            $table->enum('texteditor_display', ['A', 'D'])->default('A');
        });

        Schema::create('texteditor_upload', function (Blueprint $table) {
            $table->id('texteditor_upload_id');
            $table->string('texteditor_id');
            $table->string('texteditor_upload_seq');
            $table->enum('texteditor_show', ['Y', 'N'])->default('Y');
            $table->string('texteditor_upload_name');
            $table->string('texteditor_upload_file');
            $table->enum('texteditor_display', ['A', 'D'])->default('A'); // A=แสดง, D=ลบ
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('texteditor');
        Schema::dropIfExists('texteditor_detail');
        Schema::dropIfExists('texteditor_upload');
    }
};
