<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'description'
    ];

    /**
     * Get all classes for the school.
     */
    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }
}
