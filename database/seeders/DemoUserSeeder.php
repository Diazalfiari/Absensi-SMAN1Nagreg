<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@smansan.sch.id'],
            [
                'name' => 'Administrator',
                'email' => 'admin@smansan.sch.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Teacher User
        User::updateOrCreate(
            ['email' => 'teacher@smansan.sch.id'],
            [
                'name' => 'Teacher Demo',
                'email' => 'teacher@smansan.sch.id',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'email_verified_at' => now(),
            ]
        );

        // Create Student User
        User::updateOrCreate(
            ['email' => 'student@smansan.sch.id'],
            [
                'name' => 'Student Demo',
                'email' => 'student@smansan.sch.id',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Demo users created successfully!');
        $this->command->info('Admin: admin@smansan.sch.id / password');
        $this->command->info('Teacher: teacher@smansan.sch.id / password');
        $this->command->info('Student: student@smansan.sch.id / password');
    }
}
