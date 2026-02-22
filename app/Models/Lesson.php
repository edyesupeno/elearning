<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'content', 'order', 'attachments'];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
