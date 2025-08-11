<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'credit_hours',
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_hours' => 'integer'
    ];

    /**
     * Get all schedules for this subject.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
