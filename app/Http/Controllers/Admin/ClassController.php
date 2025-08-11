<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Exports\ClassesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::withCount(['students' => function($query) {
                              $query->where('status', 'active');
                          }])
                          ->with('homeroomTeacher')
                          ->where('is_active', true)
                          ->orderBy('grade')
                          ->orderBy('name')
                          ->paginate(15);
        
        $totalClasses = ClassRoom::where('is_active', true)->count();
        $totalStudents = Student::where('status', 'active')->count();
        
        return view('admin.classes.index', compact('classes', 'totalClasses', 'totalStudents'));
    }
    
    public function create()
    {
        try {
            // Get available teachers for homeroom teacher selection
            $teachers = Teacher::with('user')
                              ->where('status', 'active')
                              ->orderBy('name')
                              ->get();
        } catch (\Exception $e) {
            $teachers = collect();
        }
        
        return view('admin.classes.create', compact('teachers'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
            'grade' => 'required|in:X,XI,XII',
            'capacity' => 'required|integer|min:1|max:50',
            'academic_year' => 'required|string|max:20',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();
            
            $class = ClassRoom::create([
                'name' => $request->name,
                'code' => $request->code ?: $this->generateClassCode($request->grade, $request->name),
                'grade' => $request->grade,
                'capacity' => $request->capacity,
                'academic_year' => $request->academic_year,
                'homeroom_teacher_id' => $request->homeroom_teacher_id,
                'room' => $request->room,
                'description' => $request->description,
                'school_id' => 1, // Default school
                'is_active' => true
            ]);

            DB::commit();
            
            return redirect()->route('admin.classes.index')
                           ->with('success', 'Data kelas berhasil ditambahkan!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal menambahkan data kelas: ' . $e->getMessage()]);
        }
    }
    
    public function show(ClassRoom $class)
    {
        $class->load(['students.user']);
        
        $students = $class->students()
                         ->with('user')
                         ->where('status', 'active')
                         ->orderBy('name')
                         ->get();
        
        // Get schedules for this class
        $schedules = \App\Models\Schedule::with(['subject', 'teacher'])
                                       ->where('class_id', $class->id)
                                       ->where('is_active', true)
                                       ->orderBy('day')
                                       ->orderBy('start_time')
                                       ->get();
        
        return view('admin.classes.show', compact('class', 'students', 'schedules'));
    }
    
    public function edit(ClassRoom $class)
    {
        try {
            // Get available teachers for homeroom teacher selection
            $teachers = Teacher::with('user')
                              ->where('status', 'active')
                              ->orderBy('name')
                              ->get();
        } catch (\Exception $e) {
            $teachers = collect();
        }
        
        return view('admin.classes.edit', compact('class', 'teachers'));
    }
    
    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('classes', 'name')->ignore($class->id)],
            'grade' => 'required|in:X,XI,XII',
            'capacity' => 'required|integer|min:1|max:50',
            'academic_year' => 'required|string|max:20',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();
            
            $class->update([
                'name' => $request->name,
                'code' => $request->code ?: $this->generateClassCode($request->grade, $request->name),
                'grade' => $request->grade,
                'capacity' => $request->capacity,
                'academic_year' => $request->academic_year,
                'homeroom_teacher_id' => $request->homeroom_teacher_id,
                'room' => $request->room,
                'description' => $request->description,
                'is_active' => $request->is_active
            ]);

            DB::commit();
            
            return redirect()->route('admin.classes.index')
                           ->with('success', 'Data kelas berhasil diperbarui!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal memperbarui data kelas: ' . $e->getMessage()]);
        }
    }
    
    public function destroy(ClassRoom $class)
    {
        try {
            DB::beginTransaction();
            
            // Check if there are active students in this class
            $activeStudentsCount = $class->students()->where('status', 'active')->count();
            
            if ($activeStudentsCount > 0) {
                return back()->withErrors([
                    'error' => "Tidak dapat menghapus kelas karena masih ada {$activeStudentsCount} siswa aktif di kelas ini. Silahkan pindahkan siswa terlebih dahulu."
                ]);
            }
            
            // Hard delete - permanently remove from database
            $class->delete();
            
            DB::commit();
            
            return redirect()->route('admin.classes.index')
                           ->with('success', 'Data kelas berhasil dihapus permanen!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menghapus data kelas: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Generate class code automatically
     */
    private function generateClassCode($grade, $name)
    {
        // Extract number from name (e.g., "X-1", "XI-2", "XII-3" -> "1", "2", "3")
        $match = preg_match('/(\d+)$/', $name, $matches);
        $number = $match ? $matches[1] : '1';
        
        // Generate code (e.g., "X" + "1" -> "X1", "XI" + "2" -> "XI2")
        return $grade . $number;
    }

    /**
     * Export classes data to Excel/CSV
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'xlsx');
            $filename = 'data-kelas-' . date('Y-m-d');
            
            switch ($format) {
                case 'csv':
                    return Excel::download(new ClassesExport, $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
                case 'xlsx':
                default:
                    return Excel::download(new ClassesExport, $filename . '.xlsx');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengexport data: ' . $e->getMessage()]);
        }
    }
}
