<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create student records for existing student users
        $studentUsers = \App\Models\User::where('role', 'student')->get();
        
        foreach ($studentUsers as $index => $user) {
            $classId = ($index % 2) + 1; // Distribusi ke kelas 1 dan 2
            
            \App\Models\Student::create([
                'nisn' => '00002024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'nis' => '2024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $index % 2 == 0 ? 'L' : 'P',
                'birth_date' => now()->subYears(17)->subDays(rand(1, 365)),
                'birth_place' => 'Bandung',
                'address' => 'Jl. Contoh No. ' . ($index + 1) . ', Nagreg, Bandung',
                'phone' => '0812345678' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'parent_phone' => '0813456789' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'class_id' => $classId,
                'user_id' => $user->id,
                'entry_date' => now()->subMonths(6),
                'status' => 'active'
            ]);
        }
    }
}
