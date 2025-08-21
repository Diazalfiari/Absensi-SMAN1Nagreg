<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;

class AttendanceController extends Controller
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

        // Get teacher's schedules for filter
        $schedules = Schedule::where('teacher_id', $teacher->id)
                           ->with(['subject', 'classRoom'])
                           ->get();

        // Build query
        $query = Attendance::whereHas('schedule', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with(['student', 'schedule.subject', 'schedule.classRoom']);

        // Apply filters
        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->schedule_id);
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get attendances with pagination
        $attendances = $query->latest()->paginate(15);

        // Statistics
        $totalAttendances = Attendance::whereHas('schedule', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->count();

        $attendanceStats = [
            'hadir' => Attendance::whereHas('schedule', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->where('status', 'hadir')->count(),
            'sakit' => Attendance::whereHas('schedule', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->where('status', 'sakit')->count(),
            'izin' => Attendance::whereHas('schedule', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->where('status', 'izin')->count(),
            'alpha' => Attendance::whereHas('schedule', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->where('status', 'alpha')->count(),
        ];

        return view('teacher.attendances.index', compact(
            'attendances', 
            'schedules', 
            'totalAttendances',
            'attendanceStats',
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

        $attendance = Attendance::whereHas('schedule', function($q) use ($teacher) {
                                $q->where('teacher_id', $teacher->id);
                            })
                            ->where('id', $id)
                            ->with(['student', 'schedule.subject', 'schedule.classRoom'])
                            ->firstOrFail();

        return view('teacher.attendances.show', compact('attendance', 'teacher'));
    }
}
