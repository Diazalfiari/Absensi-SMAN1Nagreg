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
            // Log the incoming request for debugging
            Log::info('Attendance submission attempt', [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['photo']) // Exclude photo for log size
            ]);

            $request->validate([
                'schedule_id' => 'required|exists:schedules,id',
                'status' => 'required|in:hadir,sakit,izin,alpha',
                'photo' => 'nullable|string', // Base64 photo data - optional for non-hadir status
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'notes' => 'nullable|string|max:255'
            ]);

            // Validate photo is required for 'hadir' status
            if ($request->status === 'hadir' && empty($request->photo)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Foto wajib untuk status Hadir!'
                ]);
            }

            // Get current user's student record
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
            
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan!']);
            }

            // Validate schedule
            $schedule = Schedule::findOrFail($request->schedule_id);
            $now = Carbon::now();
            
            // Parse schedule times properly
            $scheduleStart = Carbon::createFromFormat('H:i:s', $schedule->start_time);
            $scheduleEnd = Carbon::createFromFormat('H:i:s', $schedule->end_time);
            
            // Set the date to today for comparison
            $scheduleStart->setDateFrom($now);
            $scheduleEnd->setDateFrom($now);

            // Check if within attendance window (15 minutes before to end of class)
            $attendanceStart = $scheduleStart->copy()->subMinutes(15);
            $attendanceEnd = $scheduleEnd->copy();

            // FOR TESTING: Allow attendance anytime (remove this in production)
            $testingMode = true; // Set to false in production
            
            if (!$testingMode && ($now->lt($attendanceStart) || $now->gt($attendanceEnd))) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Waktu absensi di luar jendela yang diizinkan! Anda hanya dapat absen 15 menit sebelum hingga akhir pelajaran.'
                ]);
            }

            // Check if already attended today
            $existingAttendance = Attendance::where('student_id', $student->id)
                                           ->where('schedule_id', $schedule->id)
                                           ->where('date', $now->toDateString())
                                           ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Anda sudah melakukan absensi untuk jadwal ini hari ini!'
                ]);
            }

            // Process and save photo
            $photoPath = null;
            if ($request->photo && $request->status === 'hadir') {
                $photoPath = $this->saveBase64Photo($request->photo, $student->id);
                
                if (!$photoPath) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Gagal menyimpan foto. Coba lagi.'
                    ]);
                }
            }

            // Determine if student is late
            $isLate = $now->gt($scheduleStart);
            $lateMinutes = $isLate ? $now->diffInMinutes($scheduleStart) : 0;

            // Prepare location data
            $location = null;
            if ($request->latitude && $request->longitude) {
                $location = json_encode([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]);
            }

            // Create attendance record
            $attendance = Attendance::create([
                'student_id' => $student->id,
                'schedule_id' => $schedule->id,
                'date' => $now->toDateString(),
                'status' => strtolower($request->status),
                'photo' => $photoPath,
                'location' => $location,
                'notes' => $request->notes,
                'check_in' => $now,
                'is_late' => $isLate,
                'late_minutes' => $lateMinutes
            ]);

            Log::info('Attendance submitted successfully', [
                'attendance_id' => $attendance->id,
                'student_id' => $student->id,
                'schedule_id' => $schedule->id
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Absensi berhasil disimpan!',
                'data' => [
                    'id' => $attendance->id,
                    'status' => $attendance->status,
                    'date' => $attendance->date,
                    'is_late' => $attendance->is_late,
                    'late_minutes' => $attendance->late_minutes
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Attendance validation failed', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            
            $errorMessages = collect($e->errors())->flatten()->implode(', ');
            
            return response()->json([
                'success' => false, 
                'message' => 'Data tidak valid: ' . $errorMessages
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Attendance submission error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan absensi: ' . $e->getMessage()
            ], 500);
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

        // Map English day names to Indonesian
        $dayMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        $today = $dayMapping[Carbon::now()->format('l')] ?? null;
        
        if (!$today) {
            return response()->json(['success' => false, 'message' => 'Invalid day']);
        }
        
        $schedules = Schedule::where('class_id', $student->class_id)
                           ->where('day', $today)
                           ->where('is_active', true)
                           ->with(['subject', 'teacher'])
                           ->orderBy('start_time')
                           ->get();

        // Check which schedules already have attendance today
        $schedulesWithStatus = $schedules->map(function($schedule) use ($student) {
            $attendance = Attendance::where('student_id', $student->id)
                                   ->where('schedule_id', $schedule->id)
                                   ->where('date', Carbon::today()->toDateString())
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
        
        try {
            $scheduleStart = Carbon::createFromFormat('H:i:s', $schedule->start_time);
            $scheduleEnd = Carbon::createFromFormat('H:i:s', $schedule->end_time);
            
            // Set today's date for proper comparison
            $scheduleStart->setDateFrom($now);
            $scheduleEnd->setDateFrom($now);
            
            // Can attend 15 minutes before to end of class
            $attendanceStart = $scheduleStart->copy()->subMinutes(15);
            $attendanceEnd = $scheduleEnd->copy();
            
            return $now->between($attendanceStart, $attendanceEnd);
        } catch (\Exception $e) {
            Log::error('Error parsing schedule time: ' . $e->getMessage());
            return false;
        }
    }
}