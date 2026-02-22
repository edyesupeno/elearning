<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::whereHas('role', fn($q) => $q->where('name', 'guru'))
            ->withCount('courses')
            ->latest()
            ->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $role = Role::where('name', 'guru')->first();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil ditambahkan');
    }

    public function edit(User $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $teacher->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $teacher->password,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diupdate');
    }

    public function destroy(User $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus');
    }
}
