<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'prefix')) {
                $table->string('prefix')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
                $table->index('phone');
            }
            if (!Schema::hasColumn('users', 'age')) {
                $table->unsignedSmallInteger('age')->nullable();
            }
            if (!Schema::hasColumn('users', 'house_no')) {
                $table->string('house_no')->nullable();
            }
            if (!Schema::hasColumn('users', 'village')) {
                $table->string('village')->nullable();
            }
            if (!Schema::hasColumn('users', 'subdistrict')) {
                $table->string('subdistrict')->nullable();
            }
            if (!Schema::hasColumn('users', 'district')) {
                $table->string('district')->nullable();
            }
            if (!Schema::hasColumn('users', 'province')) {
                $table->string('province')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // การลบคอลัมน์จะลบ index ของคอลัมน์นั้นไปด้วย (MySQL)
            foreach (['prefix','phone','age','house_no','village','subdistrict','district','province'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
