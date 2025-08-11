<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'name',
        'email',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'phone',
        'education_level',
        'major',
        'photo',
        'user_id',
        'hire_date',
        'status'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date'
    ];

    /**
     * Get the user associated with the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all subjects taught by this teacher.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects');
    }

    /**
     * Get all schedules for this teacher.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    /**
     * Get all attendances taken by this teacher.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'teacher_id');
    }
}
