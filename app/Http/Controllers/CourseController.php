<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;

class CourseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isTeacher()) {
            // Guru hanya melihat mapel mereka sendiri
            $courses = Course::with('teacher', 'classes')
                ->where('teacher_id', $user->id)
                ->get();
        } elseif ($user->isStudent()) {
            // Siswa melihat mapel sesuai kelas mereka
            if ($user->class_id) {
                $courses = Course::with('teacher', 'classes')
                    ->whereHas('classes', function($q) use ($user) {
                        $q->where('classes.id', $user->class_id);
                    })
                    ->get();
            } else {
                $courses = collect();
            }
        } else {
            // Admin melihat semua mapel
            $courses = Course::with('teacher', 'classes')->get();
        }
        
        return view('courses.index', compact('courses'));
    }

    
        public function create()
        {
            $teachers = \App\Models\User::where('role_id', 2)->orderBy('name')->get(); // role_id 2 = teacher
            $classes = \App\Models\ClassRoom::where('is_active', true)->orderBy('name')->get();
            return view('courses.create', compact('teachers', 'classes'));
        }


    
        public function store(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'teacher_id' => 'required|exists:users,id',
                'class_ids' => 'nullable|array',
                'class_ids.*' => 'exists:classes,id'
            ]);

            $course = Course::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'teacher_id' => $validated['teacher_id']
            ]);

            // Sync classes if provided
            if (isset($validated['class_ids'])) {
                $course->classes()->sync($validated['class_ids']);
            }

            return redirect()->route('courses.index')->with('success', 'Mapel berhasil dibuat');
        }


    
        public function show(Course $course)
        {
            $user = auth()->user();
            
            // Check access: admin can see all, teacher can see their own, student can see their class courses
            if ($user->isTeacher() && $course->teacher_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke mapel ini');
            }
            
            if ($user->isStudent()) {
                if (!$user->class_id || !$course->classes->contains('id', $user->class_id)) {
                    abort(403, 'Anda tidak memiliki akses ke mapel ini');
                }
            }
            
            $course->load('lessons', 'teacher', 'classes', 'assignments');
            $isEnrolled = auth()->user()->enrollments()->where('course_id', $course->id)->exists();
            return view('courses.show', compact('course', 'isEnrolled'));
        }



    
        public function edit(Course $course)
        {
            $user = auth()->user();
            
            // Only admin or course owner can edit
            if ($user->isTeacher() && $course->teacher_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit mapel ini');
            }
            
            $teachers = \App\Models\User::where('role_id', 2)->orderBy('name')->get();
            $classes = \App\Models\ClassRoom::where('is_active', true)->orderBy('name')->get();
            return view('courses.edit', compact('course', 'teachers', 'classes'));
        }


    
        public function update(Request $request, Course $course)
        {
            $user = auth()->user();
            
            // Only admin or course owner can update
            if ($user->isTeacher() && $course->teacher_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengupdate mapel ini');
            }
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'teacher_id' => 'required|exists:users,id',
                'class_ids' => 'nullable|array',
                'class_ids.*' => 'exists:classes,id'
            ]);

            $course->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'teacher_id' => $validated['teacher_id']
            ]);

            // Sync classes
            $course->classes()->sync($validated['class_ids'] ?? []);

            return redirect()->route('courses.show', $course)->with('success', 'Mapel berhasil diupdate');
        }


    public function destroy(Course $course)
    {
        $user = auth()->user();
        
        // Only admin or course owner can delete
        if ($user->isTeacher() && $course->teacher_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus mapel ini');
        }
        
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Mapel berhasil dihapus');
    }

    public function enroll(Course $course)
    {
        Enrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $course->id
        ]);

        return back()->with('success', 'Berhasil mendaftar kursus');
    }
}
