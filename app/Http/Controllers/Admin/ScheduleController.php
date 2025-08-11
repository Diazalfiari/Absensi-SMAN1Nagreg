<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        try {
            $schedules = Schedule::with(['subject', 'teacher', 'classRoom'])
                               ->where('is_active', true)
                               ->orderBy('day')
                               ->orderBy('start_time')
                               ->paginate(20);
            
            $totalSchedules = Schedule::where('is_active', true)->count();
            $subjects = Subject::where('is_active', true)->get();
            $classes = ClassRoom::where('is_active', true)->get();
            $teachers = User::where('role', 'teacher')->where('is_active', true)->get();
            
            // Group schedules by day
            $schedulesByDay = Schedule::with(['subject', 'teacher', 'classRoom'])
                                    ->where('is_active', true)
                                    ->orderBy('start_time')
                                    ->get()
                                    ->groupBy('day');
        } catch (\Exception $e) {
            $schedules = collect();
            $totalSchedules = 0;
            $subjects = collect();
            $classes = collect();
            $teachers = collect();
            $schedulesByDay = collect();
        }
        
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        return view('admin.schedules.index', compact(
            'schedules', 
            'totalSchedules', 
            'subjects', 
            'classes', 
            'teachers',
            'schedulesByDay',
            'days'
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
}
