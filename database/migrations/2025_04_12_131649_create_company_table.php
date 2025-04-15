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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('comment');
            $table->string('phone_number');
            $table->string('email');
            $table->string('tax_no')->nullable();
            $table->string('tckn_no')->nullable();
            $table->boolean('status')->default(true); // Burası daha sonra false çekilecek, admin kontrol edecek
            //city_id ve kind_id gibi özellikleri konuş eklenecek.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
