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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('role')->default(0)->after('password'); // 0: customer (son alıcı), 1: vendor (satıcı şirket sahibi), 2: admin, 3: super_admin
            $table->date('birthday')->nullable()->after('role');
            $table->boolean('is_approved')->default(true)->after('birthday');
            $table->boolean('is_vendor')->default(false)->after('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'birthday', 'is_approved', 'is_vendor']);

        });
    }
};
