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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Matematika, Bahasa Indonesia, dll
            $table->string('code')->unique(); // MAT, BIND, dll
            $table->text('description')->nullable();
            $table->integer('credit_hours')->default(2); // SKS
            $table->string('category')->nullable(); // Wajib, Peminatan, Muatan Lokal
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};