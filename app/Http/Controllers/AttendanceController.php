<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Schedule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendances for admin/teacher
     */
    public function index()
    {
        $attendances = Attendance::with(['student', 'schedule.subject', 'schedule.classRoom'])
                                ->latest()
                                ->paginate(15);
        
        return view('admin.attendances.index', compact('attendances'));
    }

    /**
     * Store attendance with photo capture
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'schedule_id' => 'required|exists:schedules,id',
                'status' => 'required|in:Hadir,Sakit,Izin,Alpha',
                'photo' => 'required|string', // Base64 photo data
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'notes' => 'nullable|string|max:255'
            ]);

            // Get current user's student record
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
            
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student data not found!']);
            }

            // Validate schedule
            $schedule = Schedule::findOrFail($request->schedule_id);
            $now = Carbon::now();
            $scheduleStart = Carbon::parse($schedule->start_time);
            $scheduleEnd = Carbon::parse($schedule->end_time);

            // Check if within attendance window (30 minutes before to 15 minutes after start)
            $attendanceStart = $scheduleStart->copy()->subMinutes(30);
            $attendanceEnd = $scheduleStart->copy()->addMinutes(15);

            if ($now < $attendanceStart || $now > $attendanceEnd) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Attendance time is outside the allowed window!'
                ]);
            }

                        // Check if already attended today
            $existingAttendance = Attendance::where('student_id', $student->id)
                                           ->where('schedule_id', $schedule->id)
                                           ->where('created_at', '>=', $now->startOfDay())
                                           ->where('created_at', '<=', $now->endOfDay())
                                           ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false, 
                    'message' => 'You have already submitted attendance for this schedule!'
                ]);
            }

            // Process and save photo
            $photoPath = null;
            if ($request->photo && $request->status === 'Hadir') {
                $photoPath = $this->saveBase64Photo($request->photo, $student->id);
            }

            // Create attendance record
            $attendance = Attendance::create([
                'student_id' => $student->id,
                'schedule_id' => $schedule->id,
                'status' => $request->status,
                'photo_path' => $photoPath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'notes' => $request->notes,
                'submitted_at' => $now
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Attendance submitted successfully!',
                'data' => $attendance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to submit attendance: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get attendance history for student
     */
    public function history()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->back()->with('error', 'Student data not found!');
        }

        $attendances = Attendance::where('student_id', $student->id)
                                ->with(['schedule.subject', 'schedule.classRoom'])
                                ->latest()
                                ->paginate(15);

        return view('student.attendance.history', compact('attendances'));
    }

    /**
     * Save base64 photo to storage
     */
    private function saveBase64Photo($base64Photo, $studentId)
    {
        try {
            // Remove data:image/jpeg;base64, prefix if exists
            $photo = preg_replace('/^data:image\/\w+;base64,/', '', $base64Photo);
            $photo = base64_decode($photo);

            if (!$photo) {
                throw new \Exception('Invalid photo data');
            }

            // Generate unique filename
            $filename = 'attendance_' . $studentId . '_' . time() . '_' . uniqid() . '.jpg';
            $path = 'attendance_photos/' . date('Y/m/d') . '/' . $filename;

            // Save to storage
            Storage::disk('public')->put($path, $photo);

            return $path;

        } catch (\Exception $e) {
            Log::error('Photo save error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Display attendance report for teachers/admin
     */
    public function report(Request $request)
    {
        $query = Attendance::with(['student', 'schedule.subject', 'schedule.classRoom']);
        
        // Filter by date range
        if ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date);
        }
        
        // Filter by class
        if ($request->class_id) {
            $query->whereHas('schedule.classRoom', function($q) use ($request) {
                $q->where('id', $request->class_id);
            });
        }
        
        // Filter by subject
        if ($request->subject_id) {
            $query->whereHas('schedule.subject', function($q) use ($request) {
                $q->where('id', $request->subject_id);
            });
        }

        $attendances = $query->latest()->paginate(20);
        
        return view('admin.attendances.report', compact('attendances'));
    }

    /**
     * Export attendance to Excel
     */
    public function export(Request $request)
    {
        // This would use maatwebsite/excel package
        // Implementation depends on package installation
        return response()->json(['message' => 'Export feature coming soon!']);
    }

    /**
     * Show attendance details
     */
    public function show($id)
    {
        $attendance = Attendance::with(['student', 'schedule.subject', 'schedule.classRoom'])
                                ->findOrFail($id);
        
        return view('admin.attendances.show', compact('attendance'));
    }

    /**
     * Get today's schedules for current student
     */
    public function getTodaySchedules()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }

        $today = Carbon::now()->format('l'); // Monday, Tuesday, etc.
        
        $schedules = Schedule::where('class_room_id', $student->class_room_id)
                           ->where('day', $today)
                           ->with(['subject'])
                           ->orderBy('start_time')
                           ->get();

        // Check which schedules already have attendance today
        $schedulesWithStatus = $schedules->map(function($schedule) use ($student) {
            $attendance = Attendance::where('student_id', $student->id)
                                   ->where('schedule_id', $schedule->id)
                                   ->where('created_at', '>=', Carbon::today())
                                   ->where('created_at', '<=', Carbon::today()->endOfDay())
                                   ->first();
            
            $schedule->attendance_status = $attendance ? $attendance->status : null;
            $schedule->can_attend = $this->canAttendSchedule($schedule);
            
            return $schedule;
        });

        return response()->json([
            'success' => true, 
            'schedules' => $schedulesWithStatus
        ]);
    }

    /**
     * Check if student can attend this schedule now
     */
    private function canAttendSchedule($schedule)
    {
        $now = Carbon::now();
        $scheduleStart = Carbon::parse($schedule->start_time);
        
        // Can attend 30 minutes before to 15 minutes after start time
        $attendanceStart = $scheduleStart->copy()->subMinutes(30);
        $attendanceEnd = $scheduleStart->copy()->addMinutes(15);
        
        return $now >= $attendanceStart && $now <= $attendanceEnd;
    }
}
