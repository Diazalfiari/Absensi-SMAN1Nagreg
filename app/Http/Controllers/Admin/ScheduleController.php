<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\User;
use App\Exports\SchedulesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    public function index()
    {
        // Get all schedules for grid view
        $allSchedules = Schedule::with(['subject', 'teacher', 'classRoom'])
                              ->orderBy('start_time')
                              ->get();
        
        // Get paginated schedules for list view (if needed)
        $schedules = Schedule::with(['subject', 'teacher', 'classRoom'])
                           ->orderBy('start_time')
                           ->get(); // Changed from paginate to get() for grid view
        
        // Statistics
        $totalSchedules = Schedule::count();
        $activeSchedules = Schedule::where('is_active', true)->count();
        $subjects = Subject::all();
        $classes = ClassRoom::all();
        $teachers = User::where('role', 'teacher')->get();
        
        // Group schedules by day for grid view
        $schedulesByDay = $allSchedules->groupBy('day');
        
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $timeSlots = [
            '07:00-07:45', '07:45-08:30', '08:30-09:15', '09:15-10:00',
            '10:15-11:00', '11:00-11:45', '11:45-12:30', 
            '13:00-13:45', '13:45-14:30', '14:30-15:15'
        ];
        
        return view('admin.schedules.index', compact(
            'schedules', 
            'totalSchedules', 
            'activeSchedules',
            'subjects', 
            'classes', 
            'teachers',
            'schedulesByDay',
            'days',
            'timeSlots'
        ));
    }
    
    public function show(Schedule $schedule)
    {
        $schedule->load(['subject', 'teacher', 'classRoom']);
        
        // Get students in this class
        $students = \App\Models\Student::where('class_id', $schedule->class_id)
                                     ->where('status', 'active')
                                     ->with('user')
                                     ->orderBy('name')
                                     ->get();
        
        // Get recent attendances for this schedule
        $recentAttendances = \App\Models\Attendance::with(['student.user'])
                                                  ->where('schedule_id', $schedule->id)
                                                  ->orderBy('date', 'desc')
                                                  ->limit(10)
                                                  ->get();
        
        return view('admin.schedules.show', compact('schedule', 'students', 'recentAttendances'));
    }
    
    public function create()
    {
        $subjects = Subject::all();
        $classes = ClassRoom::all();
        $teachers = User::where('role', 'teacher')->get();
        
        return view('admin.schedules.create', compact('subjects', 'classes', 'teachers'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|integer|in:1,2'
        ]);
        
        // Check for schedule conflicts
        $conflict = Schedule::where('class_id', $request->class_id)
                          ->where('day', $request->day)
                          ->where(function($query) use ($request) {
                              $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                                    ->orWhere(function($q) use ($request) {
                                        $q->where('start_time', '<=', $request->start_time)
                                          ->where('end_time', '>=', $request->end_time);
                                    });
                          })
                          ->exists();
        
        if ($conflict) {
            return back()->withInput()->withErrors([
                'time_conflict' => 'Jadwal bentrok dengan jadwal yang sudah ada untuk kelas ini pada hari dan jam yang sama.'
            ]);
        }

        try {
            DB::beginTransaction();
            
            Schedule::create([
                'subject_id' => $request->subject_id,
                'class_id' => $request->class_id,
                'teacher_id' => $request->teacher_id,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'room' => $request->room,
                'academic_year' => $request->academic_year,
                'semester' => $request->semester,
                'is_active' => true
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.schedules.index')
                           ->with('success', 'Jadwal berhasil ditambahkan!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal menambahkan jadwal: ' . $e->getMessage()]);
        }
    }
    
    public function edit(Schedule $schedule)
    {
        $subjects = Subject::all();
        $classes = ClassRoom::all();
        $teachers = User::where('role', 'teacher')->get();
        
        return view('admin.schedules.edit', compact('schedule', 'subjects', 'classes', 'teachers'));
    }
    
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|integer|in:1,2'
        ]);
        
        // Check for schedule conflicts (excluding current schedule)
        $conflict = Schedule::where('class_id', $request->class_id)
                          ->where('day', $request->day)
                          ->where('id', '!=', $schedule->id)
                          ->where(function($query) use ($request) {
                              $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                                    ->orWhere(function($q) use ($request) {
                                        $q->where('start_time', '<=', $request->start_time)
                                          ->where('end_time', '>=', $request->end_time);
                                    });
                          })
                          ->exists();
        
        if ($conflict) {
            return back()->withInput()->withErrors([
                'time_conflict' => 'Jadwal bentrok dengan jadwal yang sudah ada untuk kelas ini pada hari dan jam yang sama.'
            ]);
        }

        try {
            DB::beginTransaction();
            
            $schedule->update([
                'subject_id' => $request->subject_id,
                'class_id' => $request->class_id,
                'teacher_id' => $request->teacher_id,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'room' => $request->room,
                'academic_year' => $request->academic_year,
                'semester' => $request->semester
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.schedules.index')
                           ->with('success', 'Jadwal berhasil diperbarui!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal memperbarui jadwal: ' . $e->getMessage()]);
        }
    }
    
    public function destroy(Schedule $schedule)
    {
        try {
            DB::beginTransaction();
            
            // Check if there are attendances for this schedule
            $attendanceCount = \App\Models\Attendance::where('schedule_id', $schedule->id)->count();
            
            if ($attendanceCount > 0) {
                return back()->withErrors([
                    'error' => "Tidak dapat menghapus jadwal karena sudah ada {$attendanceCount} data absensi. Silahkan hapus data absensi terlebih dahulu."
                ]);
            }
            
            $schedule->delete();
            
            DB::commit();
            
            return redirect()->route('admin.schedules.index')
                           ->with('success', 'Jadwal berhasil dihapus!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menghapus jadwal: ' . $e->getMessage()]);
        }
    }
    
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        try {
            // Validate format
            if (!in_array($format, ['excel', 'csv'])) {
                return back()->with('error', 'Format export tidak valid. Gunakan excel atau csv.');
            }
            
            $export = new SchedulesExport();
            $filename = 'jadwal_pelajaran_' . date('Y-m-d_H-i-s');
            
            if ($format === 'csv') {
                return Excel::download($export, $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
            } else {
                return Excel::download($export, $filename . '.xlsx');
            }
        } catch (\Exception $e) {
            Log::error('Export schedules failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
}
