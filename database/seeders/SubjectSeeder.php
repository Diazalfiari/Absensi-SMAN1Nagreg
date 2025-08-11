<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Mata Pelajaran Wajib
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI', 'credit_hours' => 3, 'category' => 'Wajib'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKn', 'credit_hours' => 2, 'category' => 'Wajib'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND', 'credit_hours' => 4, 'category' => 'Wajib'],
            ['name' => 'Matematika', 'code' => 'MAT', 'credit_hours' => 4, 'category' => 'Wajib'],
            ['name' => 'Sejarah Indonesia', 'code' => 'SEJIND', 'credit_hours' => 2, 'category' => 'Wajib'],
            ['name' => 'Bahasa Inggris', 'code' => 'BING', 'credit_hours' => 2, 'category' => 'Wajib'],
            
            // Mata Pelajaran Peminatan IPA
            ['name' => 'Fisika', 'code' => 'FIS', 'credit_hours' => 4, 'category' => 'Peminatan IPA'],
            ['name' => 'Kimia', 'code' => 'KIM', 'credit_hours' => 4, 'category' => 'Peminatan IPA'],
            ['name' => 'Biologi', 'code' => 'BIO', 'credit_hours' => 4, 'category' => 'Peminatan IPA'],
            
            // Mata Pelajaran Peminatan IPS
            ['name' => 'Geografi', 'code' => 'GEO', 'credit_hours' => 4, 'category' => 'Peminatan IPS'],
            ['name' => 'Sejarah', 'code' => 'SEJ', 'credit_hours' => 4, 'category' => 'Peminatan IPS'],
            ['name' => 'Sosiologi', 'code' => 'SOS', 'credit_hours' => 4, 'category' => 'Peminatan IPS'],
            ['name' => 'Ekonomi', 'code' => 'EKO', 'credit_hours' => 4, 'category' => 'Peminatan IPS'],
            
            // Mata Pelajaran Muatan Lokal
            ['name' => 'Seni Budaya', 'code' => 'SBUD', 'credit_hours' => 2, 'category' => 'Muatan Lokal'],
            ['name' => 'Pendidikan Jasmani', 'code' => 'PJOK', 'credit_hours' => 3, 'category' => 'Muatan Lokal'],
            ['name' => 'Prakarya dan Kewirausahaan', 'code' => 'PKWU', 'credit_hours' => 2, 'category' => 'Muatan Lokal'],
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::create($subject);
        }
    }
}
