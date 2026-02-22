<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    
    Route::resource('courses', CourseController::class);
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    
    Route::get('/courses/{course}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/courses/{course}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/courses/{course}/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::get('/courses/{course}/lessons/{lesson}/preview/{index}', [LessonController::class, 'preview'])->name('lessons.preview');
    Route::get('/courses/{course}/lessons/{lesson}/download/{index}', [LessonController::class, 'download'])->name('lessons.download');
    
    // Assignment Routes
    Route::get('/courses/{course}/assignments/create', [\App\Http\Controllers\AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/courses/{course}/assignments', [\App\Http\Controllers\AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/courses/{course}/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/courses/{course}/assignments/{assignment}/edit', [\App\Http\Controllers\AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/courses/{course}/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/courses/{course}/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::get('/courses/{course}/assignments/{assignment}/preview/{index}', [\App\Http\Controllers\AssignmentController::class, 'preview'])->name('assignments.preview');
    Route::get('/courses/{course}/assignments/{assignment}/download/{index}', [\App\Http\Controllers\AssignmentController::class, 'download'])->name('assignments.download');
    Route::post('/courses/{course}/assignments/{assignment}/submit', [\App\Http\Controllers\AssignmentController::class, 'submit'])->name('assignments.submit');
    Route::get('/courses/{course}/assignments/{assignment}/submissions/{submission}/preview/{index}', [\App\Http\Controllers\AssignmentController::class, 'previewSubmission'])->name('assignments.submissions.preview');
    Route::get('/courses/{course}/assignments/{assignment}/submissions/{submission}/download/{index}', [\App\Http\Controllers\AssignmentController::class, 'downloadSubmission'])->name('assignments.submissions.download');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
        Route::resource('classes', \App\Http\Controllers\Admin\ClassController::class);
        Route::post('classes/{class}/add-student', [\App\Http\Controllers\Admin\ClassController::class, 'addStudent'])->name('classes.addStudent');
        Route::delete('classes/{class}/remove-student/{student}', [\App\Http\Controllers\Admin\ClassController::class, 'removeStudent'])->name('classes.removeStudent');
        Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/delete-image', [\App\Http\Controllers\Admin\SettingController::class, 'deleteImage'])->name('settings.deleteImage');
    });
});

