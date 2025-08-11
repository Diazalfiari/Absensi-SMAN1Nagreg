<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'grade',
        'capacity',
        'homeroom_teacher_id',
        'room',
        'description',
        'school_id',
        'academic_year',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer'
    ];

    /**
     * Get the school that owns the class.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all students in this class.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Get all schedules for this class.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    /**
     * Get the homeroom teacher for this class.
     */
    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }
}
