<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::withCount('students')->latest()->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:50',
            'major' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        ClassRoom::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function show(ClassRoom $class)
    {
        $class->load('students', 'courses.teacher');
        
        // Get students that are not in this class (including students without class and students in other classes)
        $availableStudents = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'student');
        })
        ->where(function($query) use ($class) {
            $query->whereNull('class_id')
                  ->orWhere('class_id', '!=', $class->id);
        })
        ->orderBy('name')
        ->get();
        
        return view('admin.classes.show', compact('class', 'availableStudents'));
    }

    public function addStudent(Request $request, ClassRoom $class)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id'
        ]);

        $student = \App\Models\User::findOrFail($validated['student_id']);
        $student->update(['class_id' => $class->id]);

        return redirect()->route('admin.classes.show', $class)->with('success', 'Siswa berhasil ditambahkan ke kelas');
    }

    public function removeStudent(ClassRoom $class, $studentId)
    {
        $student = \App\Models\User::findOrFail($studentId);
        $student->update(['class_id' => null]);

        return redirect()->route('admin.classes.show', $class)->with('success', 'Siswa berhasil dihapus dari kelas');
    }


    public function edit(ClassRoom $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:50',
            'major' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy(ClassRoom $class)
    {
        // Set class_id to null for all students in this class
        $class->students()->update(['class_id' => null]);
        
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dihapus');
    }
}
