<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $table = 'classes';
    
    protected $fillable = [
        'name',
        'grade',
        'major',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship: Class has many students
    public function students()
    {
        return $this->hasMany(User::class, 'class_id')->where('role_id', 3); // role_id 3 = student
    }

    // Relationship: Class has many courses (through pivot table)
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'class_course', 'class_id', 'course_id')->withTimestamps();
    }


    // Get total students count
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }
}
