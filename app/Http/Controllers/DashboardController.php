<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $stats = [
                'total_courses' => Course::count(),
                'total_teachers' => User::whereHas('role', fn($q) => $q->where('name', 'guru'))->count(),
                'total_students' => User::whereHas('role', fn($q) => $q->where('name', 'murid'))->count(),
            ];
            return view('dashboard.admin', compact('stats'));
        }
        
        if ($user->isTeacher()) {
            $courses = $user->courses()->withCount('enrollments')->get();
            return view('dashboard.teacher', compact('courses'));
        }
        
        // Student dashboard - show only courses assigned to their class
        if ($user->classRoom) {
            $enrolledCourses = $user->classRoom->courses()
                ->with(['teacher', 'lessons', 'assignments'])
                ->withCount(['lessons', 'assignments'])
                ->get();
        } else {
            $enrolledCourses = collect();
        }
        
        return view('dashboard.student', compact('enrolledCourses'));
    }
}
