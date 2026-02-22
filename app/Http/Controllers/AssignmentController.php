<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;

class AssignmentController extends Controller
{
    public function create(Course $course)
    {
        return view('assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'order' => 'required|integer',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png,mp4,mp3'
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('assignments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientOriginalExtension()
                ];
            }
        }

        $course->assignments()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'order' => $validated['order'],
            'attachments' => $attachments
        ]);

        return redirect()->route('courses.show', $course)->with('success', 'Tugas berhasil ditambahkan');
    }

    public function edit(Course $course, Assignment $assignment)
    {
        return view('assignments.edit', compact('course', 'assignment'));
    }

    public function update(Request $request, Course $course, Assignment $assignment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'order' => 'required|integer',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png,mp4,mp3',
            'keep_attachments' => 'nullable|array'
        ]);

        // Keep existing attachments that are checked
        $existingAttachments = [];
        if ($request->has('keep_attachments') && $assignment->attachments) {
            foreach ($assignment->attachments as $index => $attachment) {
                if (in_array($index, $request->keep_attachments)) {
                    $existingAttachments[] = $attachment;
                } else {
                    \Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        // Add new attachments
        $newAttachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('assignments', 'public');
                $newAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientOriginalExtension()
                ];
            }
        }

        $assignment->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'order' => $validated['order'],
            'attachments' => array_merge($existingAttachments, $newAttachments)
        ]);

        return redirect()->route('courses.show', $course)->with('success', 'Tugas berhasil diupdate');
    }

    public function destroy(Course $course, Assignment $assignment)
    {
        // Delete all attachments
        if ($assignment->attachments) {
            foreach ($assignment->attachments as $attachment) {
                \Storage::disk('public')->delete($attachment['path']);
            }
        }

        // Delete all submissions and their attachments
        foreach ($assignment->submissions as $submission) {
            if ($submission->attachments) {
                foreach ($submission->attachments as $attachment) {
                    \Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        $assignment->delete();
        return redirect()->route('courses.show', $course)->with('success', 'Tugas berhasil dihapus');
    }

    public function download(Course $course, Assignment $assignment, $index)
    {
        if (!$assignment->attachments || !isset($assignment->attachments[$index])) {
            abort(404);
        }

        $attachment = $assignment->attachments[$index];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment['name']);
    }

    public function preview(Course $course, Assignment $assignment, $index)
    {
        if (!$assignment->attachments || !isset($assignment->attachments[$index])) {
            abort(404);
        }

        $attachment = $assignment->attachments[$index];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404);
        }

        $mimeType = mime_content_type($filePath);
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $attachment['name'] . '"'
        ]);
    }

    public function show(Course $course, Assignment $assignment)
    {
        $assignment->load('submissions.user');
        $userSubmission = null;
        
        if (auth()->user()->isStudent()) {
            $userSubmission = $assignment->getSubmissionByUser(auth()->id());
        }

        return view('assignments.show', compact('course', 'assignment', 'userSubmission'));
    }

    public function submit(Request $request, Course $course, Assignment $assignment)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png,mp4,mp3'
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('submissions', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientOriginalExtension()
                ];
            }
        }

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'user_id' => auth()->id()
            ],
            [
                'notes' => $validated['notes'],
                'attachments' => $attachments,
                'submitted_at' => now()
            ]
        );

        return redirect()->route('assignments.show', [$course, $assignment])->with('success', 'Tugas berhasil dikumpulkan');
    }

    public function downloadSubmission(Course $course, Assignment $assignment, AssignmentSubmission $submission, $index)
    {
        if (!$submission->attachments || !isset($submission->attachments[$index])) {
            abort(404);
        }

        $attachment = $submission->attachments[$index];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment['name']);
    }

    public function previewSubmission(Course $course, Assignment $assignment, AssignmentSubmission $submission, $index)
    {
        if (!$submission->attachments || !isset($submission->attachments[$index])) {
            abort(404);
        }

        $attachment = $submission->attachments[$index];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404);
        }

        $mimeType = mime_content_type($filePath);
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $attachment['name'] . '"'
        ]);
    }
}
