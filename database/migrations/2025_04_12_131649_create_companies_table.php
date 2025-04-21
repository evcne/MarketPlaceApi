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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('comment')->nullable();
            $table->string('phone_number');
            $table->string('email');
            $table->string('tax_no')->nullable();    // Tüzel kişilikler için
            $table->string('tckn_no')->nullable();   // Gerçek kişiler için
            $table->unsignedTinyInteger('tax_status')->default(0); // 0: individual, 1: corporate
            $table->boolean('is_approved')->default(false); // admin onayı
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
