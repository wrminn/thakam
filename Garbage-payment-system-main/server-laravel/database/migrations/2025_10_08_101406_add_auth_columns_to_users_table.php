<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // password
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable(); // ตั้ง nullable ไว้ก่อน กันข้อมูลเก่า
            }

            // remember_token (ถ้าใช้ Sanctum/Session)
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }

            // email_verified_at (ไม่จำเป็น แต่เป็นคอลัมน์มาตรฐาน)
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }

            // timestamps
            if (!Schema::hasColumn('users', 'created_at') && !Schema::hasColumn('users', 'updated_at')) {
                $table->timestamps(); // เพิ่มทั้งสองคอลัมน์
            } else {
                if (!Schema::hasColumn('users', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('users', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            }

            // ถ้าตารางยังไม่มี unique index ที่ email และคุณ “มั่นใจว่าไม่มีข้อมูลซ้ำ”
            // สามารถเปิดบรรทัดนี้ได้ (คอมเมนต์ไว้ก่อนเพื่อความปลอดภัย)
            // if (!Schema::hasColumn('users', 'email')) { $table->string('email')->nullable(); }
            // $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            // ไม่ลบ password/timestamps ย้อนหลัง (กันข้อมูลผู้ใช้หาย)
        });
    }
};
