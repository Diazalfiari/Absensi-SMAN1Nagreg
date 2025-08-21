<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
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

        // Get all teaching schedules grouped by day
        $schedules = Schedule::where('teacher_id', $teacher->id)
                           ->with(['subject', 'classRoom'])
                           ->orderBy('day')
                           ->orderBy('start_time')
                           ->get();

        // Group schedules by day
        $schedulesByDay = $schedules->groupBy('day');

        // Days of the week
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('teacher.schedules.index', compact('schedulesByDay', 'days', 'teacher'));
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

        $schedule = Schedule::where('teacher_id', $teacher->id)
                          ->where('id', $id)
                          ->with(['subject', 'classRoom'])
                          ->firstOrFail();

        return view('teacher.schedules.show', compact('schedule', 'teacher'));
    }
}
