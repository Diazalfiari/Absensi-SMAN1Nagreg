<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Debug route untuk memeriksa demo users
Route::get('/debug-users', function () {
    $admin = \App\Models\User::where('email', 'admin@smansan.sch.id')->first();
    $teacher = \App\Models\User::where('email', 'teacher@smansan.sch.id')->first();
    $student = \App\Models\User::where('email', 'student@smansan.sch.id')->first();
    
    $output = '<h2>Debug Demo Users</h2>';
    
    if ($admin) {
        $output .= '<p><strong>Admin Found:</strong> ' . $admin->email . ' - Role: ' . $admin->role . '</p>';
    } else {
        $output .= '<p><strong>Admin NOT found!</strong></p>';
    }
    
    if ($teacher) {
        $output .= '<p><strong>Teacher Found:</strong> ' . $teacher->email . ' - Role: ' . $teacher->role . '</p>';
    } else {
        $output .= '<p><strong>Teacher NOT found!</strong></p>';
    }
    
    if ($student) {
        $output .= '<p><strong>Student Found:</strong> ' . $student->email . ' - Role: ' . $student->role . '</p>';
    } else {
        $output .= '<p><strong>Student NOT found!</strong></p>';
    }
    
    $output .= '<hr><p><a href="/create-demo-users">Create Demo Users</a> | <a href="/login">Go to Login</a></p>';
    
    return $output;
});

// Route untuk test login manual
Route::get('/test-login', function () {
    $credentials = [
        'email' => 'admin@smansan.sch.id',
        'password' => 'password'
    ];
    
    $user = \App\Models\User::where('email', 'admin@smansan.sch.id')->first();
    
    $output = '<h2>Test Login Debug</h2>';
    
    if ($user) {
        $output .= '<p><strong>User found:</strong> ' . $user->email . '</p>';
        $output .= '<p><strong>Role:</strong> ' . $user->role . '</p>';
        $output .= '<p><strong>Password Hash:</strong> ' . substr($user->password, 0, 50) . '...</p>';
        
        // Test password verification
        $passwordCheck = \Illuminate\Support\Facades\Hash::check('password', $user->password);
        $output .= '<p><strong>Password Check:</strong> ' . ($passwordCheck ? 'VALID' : 'INVALID') . '</p>';
        
        // Test authentication
        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $output .= '<p><strong>Auth Attempt:</strong> SUCCESS</p>';
            $output .= '<p><a href="/dashboard">Go to Dashboard</a></p>';
        } else {
            $output .= '<p><strong>Auth Attempt:</strong> FAILED</p>';
        }
    } else {
        $output .= '<p><strong>User NOT found!</strong></p>';
    }
    
    $output .= '<hr><p><a href="/create-demo-users">Create Demo Users</a> | <a href="/login">Go to Login</a></p>';
    
    return $output;
});

// Route untuk membuat ulang demo users
Route::get('/create-demo-users', function () {
    // Hapus user lama jika ada
    \App\Models\User::where('email', 'admin@smansan.sch.id')->delete();
    \App\Models\User::where('email', 'teacher@smansan.sch.id')->delete();
    \App\Models\User::where('email', 'student@smansan.sch.id')->delete();
    
    // Create demo school first
    $school = null;
    try {
        $school = \App\Models\School::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'SMA Negeri Samudra Alam Nusantara',
                'address' => 'Jl. Raya Nagreg, Bandung',
                'phone' => '022-1234567',
                'email' => 'info@smansan.sch.id',
                'website' => 'www.smansan.sch.id',
                'principal_name' => 'Dr. H. Ahmad Suryadi, M.Pd',
                'is_active' => true
            ]
        );
    } catch (\Exception $e) {
        // Create a simple school entry if the full model fails
        try {
            \Illuminate\Support\Facades\DB::table('schools')->updateOrInsert(
                ['id' => 1],
                [
                    'name' => 'SMA Negeri Samudra Alam Nusantara',
                    'address' => 'Jl. Raya Nagreg, Bandung',
                    'phone' => '022-1234567',
                    'email' => 'info@smansan.sch.id',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        } catch (\Exception $e2) {
            // If still fails, we'll use school_id = 1 anyway
        }
    }

    // Create demo subjects
    try {
        $subjects = [
            ['name' => 'Matematika', 'code' => 'MAT', 'description' => 'Mata pelajaran Matematika'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN', 'description' => 'Mata pelajaran Bahasa Indonesia'],
            ['name' => 'Bahasa Inggris', 'code' => 'BING', 'description' => 'Mata pelajaran Bahasa Inggris'],
            ['name' => 'Fisika', 'code' => 'FIS', 'description' => 'Mata pelajaran Fisika'],
            ['name' => 'Kimia', 'code' => 'KIM', 'description' => 'Mata pelajaran Kimia'],
            ['name' => 'Biologi', 'code' => 'BIO', 'description' => 'Mata pelajaran Biologi'],
        ];

        foreach ($subjects as $index => $subjectData) {
            \App\Models\Subject::updateOrCreate(
                ['code' => $subjectData['code']],
                [
                    'name' => $subjectData['name'],
                    'code' => $subjectData['code'],
                    'description' => $subjectData['description'],
                    'school_id' => 1,
                    'is_active' => true
                ]
            );
        }
    } catch (\Exception $e) {
        // Ignore if Subject table doesn't exist yet
    }

    // Create demo classes 
    try {
        \App\Models\ClassRoom::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'X-1', 
                'code' => 'X1',
                'grade' => 'X',
                'capacity' => 30,
                'room' => 'Ruang 101',
                'description' => 'Kelas X paralel 1',
                'school_id' => 1,
                'academic_year' => '2024/2025',
                'is_active' => true
            ]
        );
        \App\Models\ClassRoom::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'X-2', 
                'code' => 'X2',
                'grade' => 'X',
                'capacity' => 30,
                'room' => 'Ruang 102',
                'description' => 'Kelas X paralel 2',
                'school_id' => 1,
                'academic_year' => '2024/2025',
                'is_active' => true
            ]
        );
        \App\Models\ClassRoom::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'XI-1', 
                'code' => 'XI1',
                'grade' => 'XI',
                'capacity' => 30,
                'room' => 'Ruang 201',
                'description' => 'Kelas XI paralel 1',
                'school_id' => 1,
                'academic_year' => '2024/2025',
                'is_active' => true
            ]
        );
        \App\Models\ClassRoom::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'XI-2', 
                'code' => 'XI2',
                'grade' => 'XI',
                'capacity' => 30,
                'room' => 'Ruang 202',
                'description' => 'Kelas XI paralel 2',
                'school_id' => 1,
                'academic_year' => '2024/2025',
                'is_active' => true
            ]
        );
        \App\Models\ClassRoom::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'XII-1', 
                'code' => 'XII1',
                'grade' => 'XII',
                'capacity' => 30,
                'room' => 'Ruang 301',
                'description' => 'Kelas XII paralel 1',
                'school_id' => 1,
                'academic_year' => '2024/2025',
                'is_active' => true
            ]
        );
        \App\Models\ClassRoom::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'XII-2', 
                'code' => 'XII2',
                'grade' => 'XII',
                'capacity' => 30,
                'room' => 'Ruang 302',
                'description' => 'Kelas XII paralel 2',
                'school_id' => 1,
                'academic_year' => '2024/2025',
                'is_active' => true
            ]
        );
    } catch (\Exception $e) {
        // Ignore if ClassRoom table doesn't exist yet
    }
    
    // Buat user baru
    \App\Models\User::create([
        'name' => 'Administrator',
        'email' => 'admin@smansan.sch.id',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    \App\Models\User::create([
        'name' => 'Teacher Demo',
        'email' => 'teacher@smansan.sch.id',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role' => 'teacher',
        'email_verified_at' => now(),
    ]);

    // Buat teacher record untuk user teacher
    try {
        $teacherUser = \App\Models\User::where('email', 'teacher@smansan.sch.id')->first();
        if ($teacherUser) {
            $teacher = \App\Models\Teacher::updateOrCreate(
                ['user_id' => $teacherUser->id],
                [
                    'nip' => '197012312006041001',
                    'name' => 'Teacher Demo',
                    'email' => 'teacher@smansan.sch.id',
                    'gender' => 'L',
                    'birth_date' => '1970-12-31',
                    'birth_place' => 'Bandung',
                    'address' => 'Jl. Demo Teacher No. 1, Nagreg, Bandung',
                    'phone' => '081234567899',
                    'education_level' => 'S1',
                    'major' => 'Pendidikan Matematika',
                    'user_id' => $teacherUser->id,
                    'hire_date' => '2010-01-01',
                    'status' => 'active'
                ]
            );

            // Attach some subjects to the teacher
            try {
                $mathSubject = \App\Models\Subject::where('code', 'MAT')->first();
                $physicsSubject = \App\Models\Subject::where('code', 'FIS')->first();
                
                if ($mathSubject) {
                    $teacher->subjects()->syncWithoutDetaching([$mathSubject->id]);
                }
                if ($physicsSubject) {
                    $teacher->subjects()->syncWithoutDetaching([$physicsSubject->id]);
                }
            } catch (\Exception $e) {
                // Ignore if subjects don't exist
            }
        }
    } catch (\Exception $e) {
        // Jika gagal buat teacher record, tidak masalah
    }

    $studentUser = \App\Models\User::create([
        'name' => 'Student Demo',
        'email' => 'student@smansan.sch.id',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role' => 'student',
        'email_verified_at' => now(),
    ]);

    // Buat student record untuk user student
    try {
        \App\Models\Student::updateOrCreate(
            ['user_id' => $studentUser->id],
            [
                'nisn' => '0000202400001',
                'nis' => '202400001',
                'name' => 'Student Demo',
                'email' => 'student@smansan.sch.id',
                'gender' => 'L',
                'birth_date' => now()->subYears(17),
                'birth_place' => 'Bandung',
                'address' => 'Jl. Demo No. 1, Nagreg, Bandung',
                'phone' => '081234567890',
                'parent_phone' => '081234567891',
                'class_id' => 1,
                'user_id' => $studentUser->id,
                'entry_date' => now()->subMonths(6),
                'status' => 'active'
            ]
        );

        // Buat siswa demo tambahan dengan status inactive
        $inactiveUser = \App\Models\User::create([
            'name' => 'Student Inactive Demo',
            'email' => 'student.inactive@smansan.sch.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        \App\Models\Student::updateOrCreate(
            ['user_id' => $inactiveUser->id],
            [
                'nisn' => '0000202400002',
                'nis' => '202400002',
                'name' => 'Student Inactive Demo',
                'email' => 'student.inactive@smansan.sch.id',
                'gender' => 'P',
                'birth_date' => now()->subYears(16),
                'birth_place' => 'Jakarta',
                'address' => 'Jl. Demo No. 2, Jakarta',
                'phone' => '081234567892',
                'parent_phone' => '081234567893',
                'class_id' => 2,
                'user_id' => $inactiveUser->id,
                'entry_date' => now()->subMonths(8),
                'status' => 'inactive' // Status tidak aktif
            ]
        );
    } catch (\Exception $e) {
        // Jika gagal buat student record, tidak masalah
    }

    return '<h2>Demo Users, School & Classes Created Successfully!</h2>
           <p><strong>Admin:</strong> admin@smansan.sch.id / password</p>
           <p><strong>Teacher:</strong> teacher@smansan.sch.id / password</p>
           <p><strong>Student Active:</strong> student@smansan.sch.id / password</p>
           <p><strong>Student Inactive:</strong> student.inactive@smansan.sch.id / password</p>
           <p><strong>School:</strong> SMA Negeri Samudra Alam Nusantara created</p>
           <p><strong>Classes:</strong> 6 demo classes created (X IPA 1, X IPA 2, XI IPA 1, XI IPA 2, XII IPA 1, XII IPA 2)</p>
           <p><strong>Students:</strong> 2 demo students created (1 active, 1 inactive)</p>
           <hr>
           <p><a href="/debug-users">Debug Users</a> | <a href="/test-login">Test Login</a> | <a href="/login">Go to Login</a></p>';
});

// Route untuk force login sebagai admin untuk testing
Route::get('/force-login-admin', function () {
    $user = \App\Models\User::where('email', 'admin@smansan.sch.id')->first();
    
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Force logged in as admin!');
    }
    
    return 'Admin user not found! <a href="/create-demo-users">Create Demo Users</a>';
});

// Route untuk force login sebagai teacher untuk testing
Route::get('/force-login-teacher', function () {
    $user = \App\Models\User::where('email', 'teacher@smansan.sch.id')->first();
    
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Force logged in as teacher!');
    }
    
    return 'Teacher user not found! <a href="/create-demo-users">Create Demo Users</a>';
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Students routes
        Route::get('/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{student}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('students.destroy');
        
        // Student Export routes
        Route::get('/students/export/excel', [\App\Http\Controllers\Admin\StudentController::class, 'export'])->name('students.export');
        Route::get('/students/export/google-sheets', [\App\Http\Controllers\Admin\StudentController::class, 'exportToGoogleSheets'])->name('students.export.google');
        
        // Teachers routes
        Route::get('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('teachers.index');
        Route::get('/teachers/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teachers.store');
        Route::get('/teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'show'])->name('teachers.show');
        Route::get('/teachers/{teacher}/edit', [\App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('/teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('teachers.destroy');
        
        // Teacher Export routes
        Route::get('/teachers/export/excel', [\App\Http\Controllers\Admin\TeacherController::class, 'export'])->name('teachers.export');
        Route::get('/teachers/export/google-sheets', [\App\Http\Controllers\Admin\TeacherController::class, 'exportToGoogleSheets'])->name('teachers.export.google');
        
        // Classes routes
        Route::get('/classes', [\App\Http\Controllers\Admin\ClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/create', [\App\Http\Controllers\Admin\ClassController::class, 'create'])->name('classes.create');
        Route::post('/classes', [\App\Http\Controllers\Admin\ClassController::class, 'store'])->name('classes.store');
        Route::get('/classes/{class}', [\App\Http\Controllers\Admin\ClassController::class, 'show'])->name('classes.show');
        Route::get('/classes/{class}/edit', [\App\Http\Controllers\Admin\ClassController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{class}', [\App\Http\Controllers\Admin\ClassController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{class}', [\App\Http\Controllers\Admin\ClassController::class, 'destroy'])->name('classes.destroy');
        
        // Subjects routes
        Route::get('/subjects', [\App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('subjects.index');
        Route::get('/subjects/{subject}', [\App\Http\Controllers\Admin\SubjectController::class, 'show'])->name('subjects.show');
        
        // Schedules routes
        Route::get('/schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{schedule}', [\App\Http\Controllers\Admin\ScheduleController::class, 'show'])->name('schedules.show');
        
        // Attendances routes
        Route::get('/attendances', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/attendances/{attendance}', [\App\Http\Controllers\Admin\AttendanceController::class, 'show'])->name('attendances.show');
        
        // Reports routes
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/attendance', [\App\Http\Controllers\Admin\ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('/reports/monthly', [\App\Http\Controllers\Admin\ReportController::class, 'monthly'])->name('reports.monthly');
    });

    // Teacher routes
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teacher'])->name('dashboard');
    });

    // Student routes
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');
        Route::get('/schedule', function() {
            $user = Auth::user();
            $student = $user->student;
            
            if (!$student) {
                return redirect()->route('student.dashboard')->with('error', 'Data siswa tidak ditemukan.');
            }
            
            // Get weekly schedules
            $weeklySchedules = \App\Models\Schedule::with(['subject', 'teacher'])
                                                  ->where('class_id', $student->class_id)
                                                  ->where('is_active', true)
                                                  ->orderBy('day')
                                                  ->orderBy('start_time')
                                                  ->get();
            
            // Get today's schedules
            $todaySchedules = $weeklySchedules->where('day', strtolower(now()->format('l')));
            
            // Get subjects for this class
            $subjects = \App\Models\Subject::whereHas('schedules', function($query) use ($student) {
                                            $query->where('class_id', $student->class_id);
                                        })
                                        ->where('is_active', true)
                                        ->get();
            
            return view('student.schedule', compact('student', 'weeklySchedules', 'todaySchedules', 'subjects'));
        })->name('schedule');
        
        Route::get('/attendance', function() {
            $user = Auth::user();
            $student = $user->student;
            
            if (!$student) {
                return redirect()->route('student.dashboard')->with('error', 'Data siswa tidak ditemukan.');
            }
            
            // Get today's schedules
            $todaySchedules = \App\Models\Schedule::with(['subject', 'teacher'])
                                                  ->where('class_id', $student->class_id)
                                                  ->where('day', strtolower(now()->format('l')))
                                                  ->where('is_active', true)
                                                  ->orderBy('start_time')
                                                  ->get();
            
            // Get recent attendances
            $recentAttendances = \App\Models\Attendance::with(['schedule.subject'])
                                                       ->where('student_id', $student->id)
                                                       ->orderBy('date', 'desc')
                                                       ->limit(10)
                                                       ->get();
            
            return view('student.attendance', compact('student', 'todaySchedules', 'recentAttendances'));
        })->name('attendance');
        
        // Attendance submission routes
        Route::post('/attendance/submit', [AttendanceController::class, 'store'])->name('attendance.submit');
        Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
        Route::get('/attendance/schedules/today', [AttendanceController::class, 'getTodaySchedules'])->name('attendance.schedules.today');
    });
});

require __DIR__.'/auth.php';