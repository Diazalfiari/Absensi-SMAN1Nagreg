<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Schedule;
use App\Exports\SubjectsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('schedules')->paginate(10);
        
        // Statistics
        $totalSubjects = Subject::count();
        $wajibCount = Subject::where('category', 'Wajib')->count();
        $peminatanCount = Subject::where('category', 'Peminatan')->count();
        $mulokCount = Subject::where('category', 'Muatan Lokal')->count();
        
        return view('admin.subjects.index', compact(
            'subjects', 
            'totalSubjects', 
            'wajibCount', 
            'peminatanCount', 
            'mulokCount'
        ));
    }
    
    public function create()
    {
        return view('admin.subjects.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'code' => 'required|string|max:10|unique:subjects,code',
            'description' => 'nullable|string|max:500',
            'credit_hours' => 'required|integer|min:1|max:10',
            'category' => 'required|in:Wajib,Peminatan,Muatan Lokal'
        ]);

        try {
            DB::beginTransaction();
            
            Subject::create([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'credit_hours' => $request->credit_hours,
                'category' => $request->category,
                'is_active' => true
            ]);

            DB::commit();
            
            return redirect()->route('admin.subjects.index')
                           ->with('success', 'Data mata pelajaran berhasil ditambahkan!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal menambahkan data mata pelajaran: ' . $e->getMessage()]);
        }
    }
    
    public function show(Subject $subject)
    {
        $subject->load(['schedules.teacher', 'schedules.classRoom']);
        
        return view('admin.subjects.show', compact('subject'));
    }
    
    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }
    
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('subjects', 'name')->ignore($subject->id)],
            'code' => ['required', 'string', 'max:10', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'description' => 'nullable|string|max:500',
            'credit_hours' => 'required|integer|min:1|max:10',
            'category' => 'required|in:Wajib,Peminatan,Muatan Lokal',
            'is_active' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();
            
            $subject->update([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'credit_hours' => $request->credit_hours,
                'category' => $request->category,
                'is_active' => $request->is_active
            ]);

            DB::commit();
            
            return redirect()->route('admin.subjects.index')
                           ->with('success', 'Data mata pelajaran berhasil diperbarui!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal memperbarui data mata pelajaran: ' . $e->getMessage()]);
        }
    }
    
    public function destroy(Subject $subject)
    {
        try {
            DB::beginTransaction();
            
            // Check if there are active schedules for this subject
            $activeSchedulesCount = $subject->schedules()->where('is_active', true)->count();
            
            if ($activeSchedulesCount > 0) {
                return back()->withErrors([
                    'error' => "Tidak dapat menghapus mata pelajaran karena masih ada {$activeSchedulesCount} jadwal aktif. Silahkan hapus jadwal terlebih dahulu."
                ]);
            }
            
            // Hard delete - permanently remove from database
            $subject->delete();
            
            DB::commit();
            
            return redirect()->route('admin.subjects.index')
                           ->with('success', 'Data mata pelajaran berhasil dihapus!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menghapus data mata pelajaran: ' . $e->getMessage()]);
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
            
            $export = new SubjectsExport();
            $filename = 'mata_pelajaran_' . date('Y-m-d_H-i-s');
            
            if ($format === 'csv') {
                return Excel::download($export, $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
            } else {
                return Excel::download($export, $filename . '.xlsx');
            }
        } catch (\Exception $e) {
            Log::error('Export subjects failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
}
