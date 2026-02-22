<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;

class LessonController extends Controller
{
    public function create(Course $course)
    {
        return view('lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'order' => 'required|integer',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png,mp4,mp3'
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lessons', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientOriginalExtension()
                ];
            }
        }

        $course->lessons()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'order' => $validated['order'],
            'attachments' => $attachments
        ]);

        return redirect()->route('courses.show', $course)->with('success', 'Materi berhasil ditambahkan');
    }


    public function edit(Course $course, Lesson $lesson)
    {
        return view('lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'order' => 'required|integer',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png,mp4,mp3',
            'keep_attachments' => 'nullable|array'
        ]);

        // Keep existing attachments that are checked
        $existingAttachments = [];
        if ($request->has('keep_attachments') && $lesson->attachments) {
            foreach ($lesson->attachments as $index => $attachment) {
                if (in_array($index, $request->keep_attachments)) {
                    $existingAttachments[] = $attachment;
                } else {
                    // Delete file from storage
                    \Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        // Add new attachments
        $newAttachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lessons', 'public');
                $newAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientOriginalExtension()
                ];
            }
        }

        $lesson->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'order' => $validated['order'],
            'attachments' => array_merge($existingAttachments, $newAttachments)
        ]);

        return redirect()->route('courses.show', $course)->with('success', 'Materi berhasil diupdate');
    }


    public function destroy(Course $course, Lesson $lesson)
    {
        // Delete all attachments
        if ($lesson->attachments) {
            foreach ($lesson->attachments as $attachment) {
                \Storage::disk('public')->delete($attachment['path']);
            }
        }

        $lesson->delete();
        return redirect()->route('courses.show', $course)->with('success', 'Materi berhasil dihapus');
    }

    public function download(Course $course, Lesson $lesson, $index)
    {
        if (!$lesson->attachments || !isset($lesson->attachments[$index])) {
            abort(404);
        }

        $attachment = $lesson->attachments[$index];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment['name']);
    }

    public function preview(Course $course, Lesson $lesson, $index)
    {
        if (!$lesson->attachments || !isset($lesson->attachments[$index])) {
            abort(404);
        }

        $attachment = $lesson->attachments[$index];
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
