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
        Schema::table('classes', function (Blueprint $table) {
            $table->string('code')->nullable()->after('name'); // Kode kelas
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('teachers')->onDelete('set null')->after('capacity');
            $table->string('room')->nullable()->after('homeroom_teacher_id'); // Ruang kelas
            $table->text('description')->nullable()->after('room'); // Deskripsi kelas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['homeroom_teacher_id']);
            $table->dropColumn(['code', 'homeroom_teacher_id', 'room', 'description']);
        });
    }
};
