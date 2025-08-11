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
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
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
        return strtolower($this->day) === strtolower(now()->format('l'));
    }

    /**
     * Check if schedule is now
     */
    public function isNow()
    {
        if (!$this->isToday()) {
            return false;
        }

        $now = now()->format('H:i');
        return $now >= $this->start_time && $now <= $this->end_time;
    }
}
