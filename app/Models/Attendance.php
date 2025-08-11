<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'date',
        'status',
        'check_in',
        'check_out',
        'photo',
        'notes',
        'location',
        'is_late',
        'late_minutes'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
        'is_late' => 'boolean',
        'late_minutes' => 'integer'
    ];

    /**
     * Get the student associated with this attendance.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the schedule associated with this attendance.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'hadir' => 'success',
            'sakit' => 'warning',
            'izin' => 'info',
            'alpha' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'alpha' => 'Alpha',
            default => 'Unknown'
        };
    }
}
