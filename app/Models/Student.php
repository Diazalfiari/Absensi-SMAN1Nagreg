<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'nis',
        'name',
        'email',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'phone',
        'parent_phone',
        'photo',
        'class_id',
        'user_id',
        'entry_date',
        'status'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date'
    ];

    /**
     * Get the user associated with the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the class that the student belongs to.
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get all attendances for this student.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get attendance percentage for current month
     */
    public function getAttendancePercentage($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $totalDays = $this->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->count();

        $presentDays = $this->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'hadir')
            ->count();

        return $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
    }
}
