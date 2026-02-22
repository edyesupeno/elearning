<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'attachments',
        'due_date',
        'order'
    ];

    protected $casts = [
        'attachments' => 'array',
        'due_date' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function getSubmissionByUser($userId)
    {
        return $this->submissions()->where('user_id', $userId)->first();
    }
}
