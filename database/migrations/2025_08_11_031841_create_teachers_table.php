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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique(); // Nomor Induk Pegawai
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('gender', ['L', 'P']); // L = Laki-laki, P = Perempuan
            $table->date('birth_date');
            $table->string('birth_place');
            $table->text('address');
            $table->string('phone', 20)->nullable();
            $table->string('education_level'); // S1, S2, S3, dll
            $table->string('major'); // Jurusan pendidikan
            $table->string('photo')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('hire_date'); // Tanggal mulai mengajar
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
