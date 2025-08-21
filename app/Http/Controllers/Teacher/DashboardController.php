<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
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

        // Get today's teaching schedules
        $today = Carbon::now()->format('l'); // Get day name
        $todaySchedules = Schedule::where('teacher_id', $teacher->id)
                                ->where('day', $today)
                                ->with(['subject', 'classRoom'])
                                ->orderBy('start_time')
                                ->get();

        // Get this week's teaching schedules  
        $weeklySchedules = Schedule::where('teacher_id', $teacher->id)
                                 ->with(['subject', 'classRoom'])
                                 ->orderBy('day')
                                 ->orderBy('start_time')
                                 ->get();

        // Get recent attendance records for teacher's classes
        $recentAttendances = Attendance::whereHas('schedule', function($query) use ($teacher) {
                                    $query->where('teacher_id', $teacher->id);
                                })
                                ->with(['student', 'schedule.subject', 'schedule.classRoom'])
                                ->latest()
                                ->take(10)
                                ->get();

        // Statistics
        $totalSchedules = Schedule::where('teacher_id', $teacher->id)->count();
        $totalStudents = Student::whereHas('classRoom.schedules', function($query) use ($teacher) {
                            $query->where('teacher_id', $teacher->id);
                        })->distinct()->count();

        // Today's attendance summary
        $todayAttendance = Attendance::whereHas('schedule', function($query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->where('date', Carbon::today()->toDateString())
                            ->get();

        $attendanceStats = [
            'hadir' => $todayAttendance->where('status', 'hadir')->count(),
            'sakit' => $todayAttendance->where('status', 'sakit')->count(),
            'izin' => $todayAttendance->where('status', 'izin')->count(),
            'alpha' => $todayAttendance->where('status', 'alpha')->count(),
        ];

        return view('teacher.dashboard-test', compact(
            'teacher',
            'todaySchedules',
            'weeklySchedules', 
            'recentAttendances',
            'totalSchedules',
            'totalStudents',
            'attendanceStats'
        ));
    }
}
