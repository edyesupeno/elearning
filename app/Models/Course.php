<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class)->orderBy('order');
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_course', 'course_id', 'class_id')->withTimestamps();
    }
}
