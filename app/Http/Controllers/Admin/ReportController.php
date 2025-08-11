<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Monthly statistics
        $currentMonth = now()->format('Y-m');
        $monthlyAttendances = Attendance::where('date', 'like', $currentMonth . '%')->get();
        
        $monthlyStats = [
            'total' => $monthlyAttendances->count(),
            'hadir' => $monthlyAttendances->where('status', 'hadir')->count(),
            'sakit' => $monthlyAttendances->where('status', 'sakit')->count(),
            'izin' => $monthlyAttendances->where('status', 'izin')->count(),
            'alpha' => $monthlyAttendances->where('status', 'alpha')->count(),
        ];
        
        // Class statistics
        $classStats = ClassRoom::with(['students' => function($query) {
                return $query->where('status', 'active');
            }])
            ->where('is_active', true)
            ->get()
            ->map(function($class) use ($currentMonth) {
                $studentIds = $class->students->pluck('id');
                $classAttendances = Attendance::whereIn('student_id', $studentIds)
                                             ->where('date', 'like', $currentMonth . '%')
                                             ->get();
                
                $totalAttendances = $classAttendances->count();
                $presentAttendances = $classAttendances->where('status', 'hadir')->count();
                
                return [
                    'class' => $class,
                    'total_students' => $class->students->count(),
                    'total_attendances' => $totalAttendances,
                    'present_attendances' => $presentAttendances,
                    'attendance_rate' => $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100, 1) : 0
                ];
            });
        
        // Weekly trend
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
        
        return view('admin.reports.index', compact('monthlyStats', 'classStats', 'weeklyData'));
    }
    
    public function daily(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        
        $attendances = Attendance::with(['student.user', 'schedule.subject', 'schedule.classRoom'])
                                ->where('date', $date)
                                ->orderBy('check_in')
                                ->get();
        
        $stats = [
            'total' => $attendances->count(),
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
        ];
        
        return view('admin.reports.daily', compact('attendances', 'stats', 'date'));
    }
    
    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        
        $attendances = Attendance::with(['student.user', 'schedule.subject', 'schedule.classRoom'])
                                ->where('date', 'like', $month . '%')
                                ->orderBy('date', 'desc')
                                ->get();
        
        $stats = [
            'total' => $attendances->count(),
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
        ];
        
        return view('admin.reports.monthly', compact('attendances', 'stats', 'month'));
    }
}
