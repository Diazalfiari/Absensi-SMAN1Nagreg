<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            // Kelas X
            ['name' => 'X IPA 1', 'grade' => 'X', 'major' => 'IPA', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'X IPA 2', 'grade' => 'X', 'major' => 'IPA', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'X IPS 1', 'grade' => 'X', 'major' => 'IPS', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'X IPS 2', 'grade' => 'X', 'major' => 'IPS', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            
            // Kelas XI
            ['name' => 'XI IPA 1', 'grade' => 'XI', 'major' => 'IPA', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'XI IPA 2', 'grade' => 'XI', 'major' => 'IPA', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'XI IPS 1', 'grade' => 'XI', 'major' => 'IPS', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'XI IPS 2', 'grade' => 'XI', 'major' => 'IPS', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            
            // Kelas XII
            ['name' => 'XII IPA 1', 'grade' => 'XII', 'major' => 'IPA', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'XII IPA 2', 'grade' => 'XII', 'major' => 'IPA', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'XII IPS 1', 'grade' => 'XII', 'major' => 'IPS', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'XII IPS 2', 'grade' => 'XII', 'major' => 'IPS', 'capacity' => 30, 'school_id' => 1, 'academic_year' => '2024/2025'],
        ];

        foreach ($classes as $class) {
            \App\Models\ClassRoom::create($class);
        }
    }
}
