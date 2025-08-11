<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\User;
use App\Exports\StudentsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index()
    {
        try {
            // Tampilkan semua siswa (aktif dan tidak aktif)
            $students = Student::with(['classRoom', 'user'])
                              ->orderBy('status', 'desc') // aktif dulu, kemudian inactive
                              ->orderBy('name')
                              ->paginate(15);
            
            $totalStudents = Student::count(); // total semua siswa
            $activeStudents = Student::where('status', 'active')->count();
            $inactiveStudents = Student::where('status', 'inactive')->count();
            $classes = ClassRoom::where('is_active', true)->get();
        } catch (\Exception $e) {
            $students = collect();
            $totalStudents = 0;
            $activeStudents = 0;
            $inactiveStudents = 0;
            $classes = collect();
        }
        
        return view('admin.students.index', compact('students', 'totalStudents', 'activeStudents', 'inactiveStudents', 'classes'));
    }
    
    public function show(Student $student)
    {
        try {
            $student->load(['classRoom', 'user', 'attendances.schedule.subject']);
            
            // Get attendance statistics
            $attendanceStats = [
                'hadir' => $student->attendances->where('status', 'hadir')->count(),
                'sakit' => $student->attendances->where('status', 'sakit')->count(),
                'izin' => $student->attendances->where('status', 'izin')->count(),
                'alpha' => $student->attendances->where('status', 'alpha')->count(),
            ];
            
            $totalAttendances = $student->attendances->count();
            $attendancePercentage = $totalAttendances > 0 ? 
                round(($attendanceStats['hadir'] / $totalAttendances) * 100, 1) : 0;
        } catch (\Exception $e) {
            $attendanceStats = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
            $attendancePercentage = 0;
        }
        
        return view('admin.students.show', compact('student', 'attendanceStats', 'attendancePercentage'));
    }
    
    public function create()
    {
        try {
            $classes = ClassRoom::where('is_active', true)->orderBy('name')->get();
        } catch (\Exception $e) {
            $classes = collect();
        }
        
        return view('admin.students.create', compact('classes'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nisn' => 'required|string|unique:students,nisn|max:20',
            'nis' => 'required|string|unique:students,nis|max:20',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'entry_date' => 'required|date',
            'password' => 'required|string|min:6|confirmed'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
                'is_active' => true,
                'email_verified_at' => now()
            ]);
            
            // Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'address' => $request->address,
                'phone' => $request->phone,
                'parent_phone' => $request->parent_phone,
                'class_id' => $request->class_id,
                'entry_date' => $request->entry_date,
                'status' => 'active'
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.students.index')
                           ->with('success', 'Data siswa berhasil ditambahkan!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menambahkan data siswa: ' . $e->getMessage()])
                        ->withInput();
        }
    }
    
    public function edit(Student $student)
    {
        try {
            $classes = ClassRoom::where('is_active', true)->orderBy('name')->get();
            $student->load(['user', 'classRoom']);
        } catch (\Exception $e) {
            $classes = collect();
        }
        
        return view('admin.students.edit', compact('student', 'classes'));
    }
    
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($student->user_id)],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('students', 'nisn')->ignore($student->id)],
            'nis' => ['required', 'string', 'max:20', Rule::unique('students', 'nis')->ignore($student->id)],
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'entry_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:6|confirmed'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update user account
            $userUpdateData = [
                'name' => $request->name,
                'email' => $request->email,
                'is_active' => $request->status === 'active'
            ];
            
            if ($request->filled('password')) {
                $userUpdateData['password'] = Hash::make($request->password);
            }
            
            $student->user->update($userUpdateData);
            
            // Update student record
            $student->update([
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'address' => $request->address,
                'phone' => $request->phone,
                'parent_phone' => $request->parent_phone,
                'class_id' => $request->class_id,
                'entry_date' => $request->entry_date,
                'status' => $request->status
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.students.index')
                           ->with('success', 'Data siswa berhasil diperbarui!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal memperbarui data siswa: ' . $e->getMessage()])
                        ->withInput();
        }
    }
    
    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();
            
            // Delete user account (this will also delete student due to foreign key)
            $student->user->delete();
            
            DB::commit();
            
            return redirect()->route('admin.students.index')
                           ->with('success', 'Data siswa berhasil dihapus!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menghapus data siswa: ' . $e->getMessage()]);
        }
    }

    /**
     * Export students data to Excel/CSV (can be uploaded to Google Sheets)
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'xlsx'); // xlsx or csv
            $filename = 'data-siswa-' . now()->format('Y-m-d-H-i-s') . '.' . $format;
            
            return Excel::download(new StudentsExport, $filename);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengexport data: ' . $e->getMessage()]);
        }
    }

    /**
     * Export students data directly to Google Sheets (if configured)
     */
    public function exportToGoogleSheets(Request $request)
    {
        try {
            // Create a new spreadsheet or update existing one
            $spreadsheetTitle = 'Data Siswa SMA N Samudra Alam Nusantara - ' . now()->format('Y-m-d H:i:s');
            
            // For now, we'll export as Excel and provide instructions to upload to Google Sheets
            $filename = 'data-siswa-for-google-sheets-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
            
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
            
            return Excel::download(new StudentsExport, $filename);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengexport ke Google Sheets: ' . $e->getMessage()]);
        }
    }
}
