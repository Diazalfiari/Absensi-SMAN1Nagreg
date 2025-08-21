<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class TeacherTestingSeeder extends Seeder
{
    public function run()
    {
        // Get the teacher user
        $teacherUser = User::where('email', 'teacher@smansan.sch.id')->first();
        if (!$teacherUser) {
            return;
        }

        // Get or create teacher record
        $teacher = Teacher::where('user_id', $teacherUser->id)->first();
        if (!$teacher) {
            $teacher = Teacher::create([
                'user_id' => $teacherUser->id,
                'name' => $teacherUser->name,
                'email' => $teacherUser->email,
                'nip' => 'T00001',
                'gender' => 'L',
                'birth_date' => '1985-01-01',
                'birth_place' => 'Jakarta',
                'address' => 'Jl. Pendidikan No. 123',
                'phone' => '081234567890',
                'education_level' => 'S1',
                'major' => 'Pendidikan Matematika',
                'hire_date' => '2020-07-01',
                'status' => 'active'
            ]);
        }

        // Create subjects if not exist
        $subjects = [
            ['name' => 'Matematika', 'code' => 'MTK', 'description' => 'Mata pelajaran Matematika'],
            ['name' => 'Fisika', 'code' => 'FIS', 'description' => 'Mata pelajaran Fisika'],
            ['name' => 'Kimia', 'code' => 'KIM', 'description' => 'Mata pelajaran Kimia'],
        ];

        foreach ($subjects as $subjectData) {
            Subject::firstOrCreate(
                ['code' => $subjectData['code']],
                $subjectData
            );
        }

        // Create classes if not exist
        $classes = [
            [
                'name' => 'XII IPA 1', 
                'grade' => 'XII',
                'school_id' => 1,
                'academic_year' => '2024/2025'
            ],
            [
                'name' => 'XII IPA 2', 
                'grade' => 'XII',
                'school_id' => 1,
                'academic_year' => '2024/2025'
            ],
            [
                'name' => 'XI IPA 1', 
                'grade' => 'XI',
                'school_id' => 1,
                'academic_year' => '2024/2025'
            ],
        ];

        foreach ($classes as $classData) {
            ClassRoom::firstOrCreate(
                ['name' => $classData['name']],
                $classData
            );
        }

        // Create students for testing
        $classRooms = ClassRoom::whereIn('name', ['XII IPA 1', 'XII IPA 2', 'XI IPA 1'])->get();
        
        foreach ($classRooms as $classRoom) {
            for ($i = 1; $i <= 5; $i++) {
                $studentUser = User::firstOrCreate([
                    'email' => "student{$classRoom->id}_{$i}@smansan.sch.id"
                ], [
                    'name' => "Siswa {$classRoom->name} {$i}",
                    'password' => bcrypt('password'),
                    'role' => 'student'
                ]);

                Student::firstOrCreate([
                    'user_id' => $studentUser->id
                ], [
                    'nisn' => "202400{$classRoom->id}{$i}001",
                    'nis' => "S{$classRoom->id}{$i}001",
                    'name' => "Siswa {$classRoom->name} {$i}",
                    'email' => $studentUser->email,
                    'class_id' => $classRoom->id,
                    'gender' => $i % 2 == 0 ? 'P' : 'L',
                    'birth_date' => '2006-01-01',
                    'birth_place' => 'Bandung',
                    'address' => 'Jl. Siswa No. ' . $i,
                    'status' => 'active'
                ]);
            }
        }

        // Create schedules for teacher
        $subjects = Subject::whereIn('code', ['MTK', 'FIS', 'KIM'])->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        foreach ($classRooms as $classRoom) {
            foreach ($subjects->take(2) as $index => $subject) { // 2 subjects per class
                Schedule::firstOrCreate([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'class_id' => $classRoom->id,
                    'day' => $days[$index % count($days)]
                ], [
                    'start_time' => sprintf('%02d:00', 8 + $index),
                    'end_time' => sprintf('%02d:40', 8 + $index),
                ]);
            }
        }

        // Create attendance records for the past month
        $schedules = Schedule::where('teacher_id', $teacher->id)->with('classRoom.students')->get();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        foreach ($schedules as $schedule) {
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                // Check if it's the right day of week
                if ($currentDate->format('l') === $schedule->day) {
                    foreach ($schedule->classRoom->students as $student) {
                        // 85% chance of being present
                        $status = rand(1, 100) <= 85 ? 'hadir' : 
                                 (rand(1, 100) <= 60 ? 'sakit' : 
                                 (rand(1, 100) <= 80 ? 'izin' : 'alpha'));
                        
                        Attendance::firstOrCreate([
                            'student_id' => $student->id,
                            'schedule_id' => $schedule->id,
                            'date' => $currentDate->format('Y-m-d')
                        ], [
                            'status' => $status,
                            'notes' => $status !== 'hadir' ? 'Keterangan ' . $status : null
                        ]);
                    }
                }
                $currentDate->addDay();
            }
        }

        $this->command->info('Teacher testing data created successfully!');
    }
}
