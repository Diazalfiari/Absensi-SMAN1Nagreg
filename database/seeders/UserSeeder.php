<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@smansan.sch.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // Create Teacher Users
        $teachers = [
            [
                'name' => 'Drs. Ahmad Suryadi, M.Pd',
                'email' => 'ahmad.suryadi@smansan.sch.id',
                'password' => bcrypt('teacher123'),
                'role' => 'teacher',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Siti Nurhalimah, S.Pd',
                'email' => 'siti.nurhalimah@smansan.sch.id',
                'password' => bcrypt('teacher123'),
                'role' => 'teacher',
                'phone' => '081234567892',
            ],
            [
                'name' => 'Dedi Hermawan, S.Pd',
                'email' => 'dedi.hermawan@smansan.sch.id',
                'password' => bcrypt('teacher123'),
                'role' => 'teacher',
                'phone' => '081234567893',
            ]
        ];

        foreach ($teachers as $teacher) {
            \App\Models\User::create($teacher);
        }

        // Create Student Users (sample)
        $students = [
            [
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@student.smansan.sch.id',
                'password' => bcrypt('student123'),
                'role' => 'student',
                'phone' => '081234567894',
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@student.smansan.sch.id',
                'password' => bcrypt('student123'),
                'role' => 'student',
                'phone' => '081234567895',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.smansan.sch.id',
                'password' => bcrypt('student123'),
                'role' => 'student',
                'phone' => '081234567896',
            ]
        ];

        foreach ($students as $student) {
            \App\Models\User::create($student);
        }
    }
}
