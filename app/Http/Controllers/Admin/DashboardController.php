<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\ClassRoom;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalStudents = Student::where('status', 'active')->count();
        $totalClasses = ClassRoom::where('is_active', true)->count();
        $totalSchedulesToday = Schedule::where('day', strtolower(now()->format('l')))
                                      ->where('is_active', true)
                                      ->count();

        // Today's attendance stats
        $today = now()->toDateString();
        $todayAttendances = Attendance::where('date', $today)->get();
        
        $attendanceStats = [
            'hadir' => $todayAttendances->where('status', 'hadir')->count(),
            'sakit' => $todayAttendances->where('status', 'sakit')->count(),
            'izin' => $todayAttendances->where('status', 'izin')->count(),
            'alpha' => $todayAttendances->where('status', 'alpha')->count(),
        ];

        // Weekly attendance chart data
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayAttendances = Attendance::where('date', $date->toDateString())->get();
            
            $weeklyData[] = [
                'date' => $date->format('M d'),
                'hadir' => $dayAttendances->where('status', 'hadir')->count(),
                'total' => $dayAttendances->count()
            ];
        }

        // Monthly class attendance stats
        $classStats = ClassRoom::with(['students' => function($query) {
                return $query->where('status', 'active');
            }])
            ->where('is_active', true)
            ->get()
            ->map(function($class) {
                $studentIds = $class->students->pluck('id');
                $monthlyAttendances = Attendance::whereIn('student_id', $studentIds)
                                               ->whereMonth('date', now()->month)
                                               ->whereYear('date', now()->year)
                                               ->get();
                
                $totalAttendances = $monthlyAttendances->count();
                $presentAttendances = $monthlyAttendances->where('status', 'hadir')->count();
                
                return [
                    'name' => $class->name,
                    'total_students' => $class->students->count(),
                    'attendance_rate' => $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100, 1) : 0
                ];
            });

        // Recent activities (latest attendances)
        $recentActivities = Attendance::with(['student.user', 'schedule.subject', 'schedule.classRoom'])
                                    ->where('date', $today)
                                    ->orderBy('check_in', 'desc')
                                    ->limit(10)
                                    ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalClasses', 
            'totalSchedulesToday',
            'attendanceStats',
            'weeklyData',
            'classStats',
            'recentActivities'
        ));
    }

    public function teacher()
    {
        try {
            $teacher = Auth::user();
            
            // Get teacher's schedules for today
            $todaySchedules = Schedule::with(['classRoom', 'subject'])
                                    ->where('teacher_id', $teacher->id)
                                    ->where('day', strtolower(now()->format('l')))
                                    ->where('is_active', true)
                                    ->orderBy('start_time')
                                    ->get();

            // Get attendance stats for teacher's classes this month
            $monthlyAttendances = Attendance::whereHas('schedule', function($query) use ($teacher) {
                                    return $query->where('teacher_id', $teacher->id);
                                })
                                ->whereMonth('date', now()->month)
                                ->whereYear('date', now()->year)
                                ->get();

            $attendanceStats = [
                'hadir' => $monthlyAttendances->where('status', 'hadir')->count(),
                'sakit' => $monthlyAttendances->where('status', 'sakit')->count(), 
                'izin' => $monthlyAttendances->where('status', 'izin')->count(),
                'alpha' => $monthlyAttendances->where('status', 'alpha')->count(),
            ];
        } catch (\Exception $e) {
            $todaySchedules = collect();
            $attendanceStats = [
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
                'alpha' => 0,
            ];
        }

        return view('teacher.dashboard', compact('todaySchedules', 'attendanceStats'));
    }

    public function student()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Get student's schedules for today
        $todaySchedules = Schedule::with(['subject', 'teacher'])
                                ->where('class_id', $student->class_id)
                                ->where('day', strtolower(now()->format('l')))
                                ->where('is_active', true)
                                ->orderBy('start_time')
                                ->get();

        // Get student's attendance stats for current month
        $monthlyAttendances = Attendance::where('student_id', $student->id)
                                      ->whereMonth('date', now()->month)
                                      ->whereYear('date', now()->year)
                                      ->get();

        $attendanceStats = [
            'hadir' => $monthlyAttendances->where('status', 'hadir')->count(),
            'sakit' => $monthlyAttendances->where('status', 'sakit')->count(),
            'izin' => $monthlyAttendances->where('status', 'izin')->count(),
            'alpha' => $monthlyAttendances->where('status', 'alpha')->count(),
        ];

        // Calculate attendance percentage
        $totalDays = $monthlyAttendances->count();
        $presentDays = $monthlyAttendances->where('status', 'hadir')->count();
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        // Get recent attendance history
        $recentAttendances = Attendance::with(['schedule.subject'])
                                     ->where('student_id', $student->id)
                                     ->orderBy('date', 'desc')
                                     ->limit(10)
                                     ->get();

        return view('student.dashboard', compact(
            'student',
            'todaySchedules', 
            'attendanceStats',
            'attendancePercentage',
            'recentAttendances'
        ));
    }
}
