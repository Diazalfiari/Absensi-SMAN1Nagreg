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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique(); // Nomor Induk Siswa Nasional
            $table->string('nis')->nullable(); // Nomor Induk Siswa
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->enum('gender', ['L', 'P']); // Laki-laki, Perempuan
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('photo')->nullable(); // Path foto profil
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('entry_date')->nullable(); // Tanggal masuk sekolah
            $table->enum('status', ['active', 'inactive', 'graduated', 'transferred'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
