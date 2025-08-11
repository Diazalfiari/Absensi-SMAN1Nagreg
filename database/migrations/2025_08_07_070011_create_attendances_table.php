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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('alpha');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('photo')->nullable(); // Foto saat absen
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->string('location')->nullable(); // Lokasi absen (GPS)
            $table->boolean('is_late')->default(false);
            $table->integer('late_minutes')->default(0);
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['student_id', 'date']);
            $table->index(['schedule_id', 'date']);
            
            // Unique constraint untuk mencegah duplikasi absen
            $table->unique(['student_id', 'schedule_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
