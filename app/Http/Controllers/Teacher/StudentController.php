<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\ClassRoom;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            // Create basic teacher record if not exists
            $teacher = Teacher::create([
                'name' => $user->name,
                'email' => $user->email,
                'user_id' => $user->id,
                'nip' => 'T' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'gender' => 'L', // Default gender, can be updated later
                'birth_date' => '1990-01-01', // Default birth date, can be updated later
                'birth_place' => 'Indonesia', // Default birth place, can be updated later
                'address' => 'Alamat belum diisi', // Default address, can be updated later
                'education_level' => 'S1', // Default education level
                'major' => 'Pendidikan', // Default major
                'hire_date' => now()->format('Y-m-d'), // Use current date as hire date
                'status' => 'active'
            ]);
        }

        // Get classes that this teacher teaches
        $teachingClasses = ClassRoom::whereHas('schedules', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->get();

        // Build query for students in teacher's classes
        $query = Student::whereHas('classRoom.schedules', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with(['user', 'classRoom']);

        // Apply filters
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('student_id', 'like', "%{$search}%");
        }

        // Get students with pagination
        $students = $query->paginate(15);

        return view('teacher.students.index', compact(
            'students', 
            'teachingClasses',
            'teacher'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            // Create basic teacher record if not exists
            $teacher = Teacher::create([
                'name' => $user->name,
                'email' => $user->email,
                'user_id' => $user->id,
                'nip' => 'T' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'gender' => 'L', // Default gender, can be updated later
                'birth_date' => '1990-01-01', // Default birth date, can be updated later
                'birth_place' => 'Indonesia', // Default birth place, can be updated later
                'address' => 'Alamat belum diisi', // Default address, can be updated later
                'education_level' => 'S1', // Default education level
                'major' => 'Pendidikan', // Default major
                'hire_date' => now()->format('Y-m-d'), // Use current date as hire date
                'status' => 'active'
            ]);
        }

        // Make sure this student is in one of teacher's classes
        $student = Student::whereHas('classRoom.schedules', function($q) use ($teacher) {
                        $q->where('teacher_id', $teacher->id);
                    })
                    ->where('id', $id)
                    ->with(['user', 'classRoom'])
                    ->firstOrFail();

        // Get student's attendance in teacher's subjects
        $attendances = $student->attendances()
                             ->whereHas('schedule', function($q) use ($teacher) {
                                 $q->where('teacher_id', $teacher->id);
                             })
                             ->with(['schedule.subject'])
                             ->latest()
                             ->paginate(10);

        // Attendance statistics
        $attendanceStats = [
            'hadir' => $student->attendances()
                             ->whereHas('schedule', function($q) use ($teacher) {
                                 $q->where('teacher_id', $teacher->id);
                             })
                             ->where('status', 'hadir')->count(),
            'sakit' => $student->attendances()
                             ->whereHas('schedule', function($q) use ($teacher) {
                                 $q->where('teacher_id', $teacher->id);
                             })
                             ->where('status', 'sakit')->count(),
            'izin' => $student->attendances()
                            ->whereHas('schedule', function($q) use ($teacher) {
                                $q->where('teacher_id', $teacher->id);
                            })
                            ->where('status', 'izin')->count(),
            'alpha' => $student->attendances()
                             ->whereHas('schedule', function($q) use ($teacher) {
                                 $q->where('teacher_id', $teacher->id);
                             })
                             ->where('status', 'alpha')->count(),
        ];

        $totalAttendance = array_sum($attendanceStats);
        $attendancePercentage = $totalAttendance > 0 ? 
            round(($attendanceStats['hadir'] / $totalAttendance) * 100, 1) : 0;

        return view('teacher.students.show', compact(
            'student', 
            'attendances', 
            'attendanceStats',
            'attendancePercentage',
            'teacher'
        ));
    }
}
