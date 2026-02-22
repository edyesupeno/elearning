<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::with(['role', 'classRoom'])
            ->whereHas('role', function($query) {
                $query->where('name', 'murid');
            })
            ->withCount('enrollments')
            ->latest()
            ->get();
            
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = ClassRoom::where('is_active', true)->orderBy('name')->get();
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $studentRole = Role::where('name', 'murid')->first();
        
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $studentRole->id,
            'class_id' => $validated['class_id'] ?? null,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function edit(User $student)
    {
        $classes = ClassRoom::where('is_active', true)->orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, User $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'password' => 'nullable|min:6',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'class_id' => $validated['class_id'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy(User $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dihapus');
    }
}
