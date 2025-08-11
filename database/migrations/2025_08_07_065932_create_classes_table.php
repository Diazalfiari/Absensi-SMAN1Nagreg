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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: XII IPA 1, XI IPS 2
            $table->string('grade'); // X, XI, XII
            $table->string('major')->nullable(); // IPA, IPS, BAHASA
            $table->integer('capacity')->default(30);
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('academic_year'); // 2024/2025
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
