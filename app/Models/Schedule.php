<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'day',
        'start_time',
        'end_time',
        'room',
        'academic_year',
        'semester',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'semester' => 'integer'
    ];

    /**
     * Get the class associated with this schedule.
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get the subject associated with this schedule.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher associated with this schedule.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get all attendances for this schedule.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Check if schedule is today
     */
    public function isToday()
    {
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
        
        $today = $dayMapping[now()->format('l')] ?? null;
        return $this->day === $today;
    }

    /**
     * Check if schedule is now
     */
    public function isNow()
    {
        if (!$this->isToday()) {
            return false;
        }

        $now = now();
        $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time);
        $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time);
        
        return $now->between($startTime, $endTime);
    }
}
