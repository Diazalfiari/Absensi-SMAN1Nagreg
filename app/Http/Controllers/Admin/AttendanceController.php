<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student.user', 'schedule.subject', 'schedule.classRoom']);
        
        // Filter by date
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        } else {
            $query->where('date', today());
        }
        
        // Filter by class
        if ($request->filled('class_id')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $attendances = $query->orderBy('date', 'desc')
                           ->orderBy('check_in', 'desc')
                           ->paginate(20);
        
        // Statistics
        $todayAttendances = Attendance::where('date', today())->get();
        $stats = [
            'total' => $todayAttendances->count(),
            'hadir' => $todayAttendances->where('status', 'hadir')->count(),
            'sakit' => $todayAttendances->where('status', 'sakit')->count(),
            'izin' => $todayAttendances->where('status', 'izin')->count(),
            'alpha' => $todayAttendances->where('status', 'alpha')->count(),
        ];
        
        $classes = ClassRoom::where('is_active', true)->get();
        
        return view('admin.attendances.index', compact('attendances', 'stats', 'classes'));
    }
    
    public function show(Attendance $attendance)
    {
        $attendance->load(['student.user', 'schedule.subject', 'schedule.classRoom', 'schedule.teacher']);
        
        return view('admin.attendances.show', compact('attendance'));
    }
}
