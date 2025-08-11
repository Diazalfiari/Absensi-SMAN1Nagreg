<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use App\Exports\TeachersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    public function index()
    {
        try {
            // Get all teachers (active and inactive)
            $teachers = Teacher::with(['user', 'subjects'])
                              ->orderBy('status', 'desc') // active first, then inactive
                              ->orderBy('name')
                              ->paginate(15);
            
            $totalTeachers = Teacher::count();
            $activeTeachers = Teacher::where('status', 'active')->count();
            $inactiveTeachers = Teacher::where('status', 'inactive')->count();
            
            // Get subjects count for statistics
            $subjects = Subject::where('is_active', true)->get();
        } catch (\Exception $e) {
            $teachers = collect();
            $totalTeachers = 0;
            $activeTeachers = 0;
            $inactiveTeachers = 0;
            $subjects = collect();
        }
        
        return view('admin.teachers.index', compact('teachers', 'totalTeachers', 'activeTeachers', 'inactiveTeachers', 'subjects'));
    }

    public function create()
    {
        try {
            $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        } catch (\Exception $e) {
            $subjects = collect();
        }
        
        return view('admin.teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:teachers,nip',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:teachers,email',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'education_level' => 'required|string',
            'major' => 'required|string',
            'hire_date' => 'required|date',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'password' => 'required|string|min:6|confirmed'
        ]);

        try {
            DB::beginTransaction();
            
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'teacher',
                'email_verified_at' => now(),
            ]);

            // Create teacher record
            $teacher = Teacher::create([
                'nip' => $request->nip,
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'address' => $request->address,
                'phone' => $request->phone,
                'education_level' => $request->education_level,
                'major' => $request->major,
                'user_id' => $user->id,
                'hire_date' => $request->hire_date,
                'status' => 'active'
            ]);

            // Attach subjects to teacher
            $teacher->subjects()->attach($request->subjects);

            DB::commit();
            
            return redirect()->route('admin.teachers.index')
                           ->with('success', 'Data guru berhasil ditambahkan!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal menambahkan data guru: ' . $e->getMessage()]);
        }
    }
    
    public function show(Teacher $teacher)
    {
        try {
            $teacher->load(['user', 'subjects', 'schedules.subject', 'schedules.classRoom']);
            
            $schedules = $teacher->schedules()
                               ->with(['subject', 'classRoom'])
                               ->where('is_active', true)
                               ->orderBy('day')
                               ->orderBy('start_time')
                               ->get();
        } catch (\Exception $e) {
            $schedules = collect();
        }
        
        return view('admin.teachers.show', compact('teacher', 'schedules'));
    }

    public function edit(Teacher $teacher)
    {
        try {
            $subjects = Subject::where('is_active', true)->orderBy('name')->get();
            $teacher->load(['subjects']);
        } catch (\Exception $e) {
            $subjects = collect();
        }
        
        return view('admin.teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'nip' => ['required', 'string', Rule::unique('teachers', 'nip')->ignore($teacher->id)],
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'email', 
                Rule::unique('users', 'email')->ignore($teacher->user_id),
                Rule::unique('teachers', 'email')->ignore($teacher->id)
            ],
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'education_level' => 'required|string',
            'major' => 'required|string',
            'hire_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        try {
            DB::beginTransaction();
            
            // Update teacher record
            $teacher->update([
                'nip' => $request->nip,
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'address' => $request->address,
                'phone' => $request->phone,
                'education_level' => $request->education_level,
                'major' => $request->major,
                'hire_date' => $request->hire_date,
                'status' => $request->status
            ]);

            // Update user account
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            
            $teacher->user->update($userData);

            // Sync subjects (this will remove old ones and add new ones)
            $teacher->subjects()->sync($request->subjects);

            DB::commit();
            
            return redirect()->route('admin.teachers.index')
                           ->with('success', 'Data guru berhasil diperbarui!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal memperbarui data guru: ' . $e->getMessage()]);
        }
    }

    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();
            
            // Remove teacher-subject relationships
            $teacher->subjects()->detach();
            
            // Delete teacher (this will also delete the user due to cascade)
            $teacher->delete();
            
            DB::commit();
            
            return redirect()->route('admin.teachers.index')
                           ->with('success', 'Data guru berhasil dihapus!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menghapus data guru: ' . $e->getMessage()]);
        }
    }

    /**
     * Export teachers data to Excel/CSV (can be uploaded to Google Sheets)
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'xlsx'); // xlsx or csv
            $filename = 'data-guru-' . now()->format('Y-m-d-H-i-s') . '.' . $format;
            
            return Excel::download(new TeachersExport, $filename);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengexport data: ' . $e->getMessage()]);
        }
    }

    /**
     * Export teachers data directly to Google Sheets (if configured)
     */
    public function exportToGoogleSheets(Request $request)
    {
        try {
            // Create a new spreadsheet or update existing one
            $spreadsheetTitle = 'Data Guru SMA N Samudra Alam Nusantara - ' . now()->format('Y-m-d H:i:s');
            
            // For now, we'll export as Excel and provide instructions to upload to Google Sheets
            $filename = 'data-guru-for-google-sheets-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
            
            // Add flash message with instructions
            session()->flash('export_info', [
                'title' => 'Export Berhasil!',
                'message' => 'File Excel telah didownload. Untuk mengupload ke Google Sheets:',
                'steps' => [
                    '1. Buka Google Drive (drive.google.com)',
                    '2. Upload file Excel yang baru didownload',
                    '3. Klik kanan file → "Buka dengan" → "Google Sheets"',
                    '4. File akan otomatis dikonversi ke Google Sheets'
                ]
            ]);
            
            return Excel::download(new TeachersExport, $filename);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengexport ke Google Sheets: ' . $e->getMessage()]);
        }
    }
}