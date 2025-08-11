<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        try {
            $subjects = Subject::withCount(['schedules' => function($query) {
                              $query->where('is_active', true);
                          }])
                          ->where('is_active', true)
                          ->orderBy('name')
                          ->paginate(15);
            
            $totalSubjects = Subject::where('is_active', true)->count();
            $totalSchedules = Schedule::where('is_active', true)->count();
        } catch (\Exception $e) {
            $subjects = collect();
            $totalSubjects = 0;
            $totalSchedules = 0;
        }
        
        return view('admin.subjects.index', compact('subjects', 'totalSubjects', 'totalSchedules'));
    }    public function show(Subject $subject)
    {
        $subject->load(['schedules.teacher', 'schedules.classRoom']);
        
        $schedules = $subject->schedules()
                           ->with(['teacher', 'classRoom'])
                           ->where('is_active', true)
                           ->orderBy('day')
                           ->orderBy('start_time')
                           ->get();
        
        return view('admin.subjects.show', compact('subject', 'schedules'));
    }
}
