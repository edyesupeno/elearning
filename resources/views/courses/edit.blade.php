@extends('layouts.app')

@section('title', 'Edit Mapel')

@section('content')
<div class="form-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <a href="{{ route('courses.show', $course) }}" class="btn btn-light rounded-circle me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="mb-1 fw-bold">Edit Mapel</h2>
            <p class="text-muted mb-0 small">Ubah informasi mata pelajaran</p>
        </div>
    </div>

    <form method="POST" action="{{ route('courses.update', $course) }}">
        @csrf
        @method('PUT')
        
        <div class="form-card mb-3">
            <div class="form-card-body">
                <label class="form-label fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                       value="{{ old('title', $course->title) }}" 
                       placeholder="Contoh: Matematika, Bahasa Indonesia" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-card mb-3">
            <div class="form-card-body">
                <label class="form-label fw-semibold">Guru Pengajar <span class="text-danger">*</span></label>
                <select name="teacher_id" class="form-select form-control-lg @error('teacher_id') is-invalid @enderror" required>
                    <option value="">Pilih Guru</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-card mb-3">
            <div class="form-card-body">
                <label class="form-label fw-semibold">Kelas</label>
                <div class="class-checkboxes">
                    @php
                        $selectedClasses = old('class_ids', $course->classes->pluck('id')->toArray());
                    @endphp
                    @forelse($classes as $class)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="class_ids[]" 
                                   value="{{ $class->id }}" id="class{{ $class->id }}"
                                   {{ in_array($class->id, $selectedClasses) ? 'checked' : '' }}>
                            <label class="form-check-label" for="class{{ $class->id }}">
                                {{ $class->name }} - Kelas {{ $class->grade }} {{ $class->major }}
                            </label>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada kelas aktif</p>
                    @endforelse
                </div>
                <small class="text-muted">Opsional - Pilih kelas yang akan mengikuti mata pelajaran ini</small>
            </div>
        </div>

        <div class="form-card mb-4">
            <div class="form-card-body">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="5" placeholder="Jelaskan tentang mata pelajaran ini...">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Opsional - Berikan deskripsi singkat tentang mata pelajaran</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill flex-fill">
                <i class="bi bi-check-circle me-2"></i>Update
            </button>
            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary btn-lg rounded-pill flex-fill">
                <i class="bi bi-x-circle me-2"></i>Batal
            </a>
        </div>
    </form>
</div>

<style>
.form-page {
    padding-bottom: 100px;
}

.page-header {
    display: flex;
    align-items: center;
}

.page-header h2 {
    color: #1a1a1a;
}

.btn-light.rounded-circle {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border: 2px solid #e9ecef;
}

.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.form-card-body {
    padding: 24px;
}

.form-label {
    color: #1a1a1a;
    margin-bottom: 8px;
}

.form-control-lg, .form-select {
    padding: 14px 16px;
    font-size: 1rem;
    border-radius: 12px;
}

.form-control, .form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 16px 12px;
    padding-right: 48px;
}

.form-select option {
    padding: 12px;
}

.class-checkboxes {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 8px;
}

.form-check {
    padding: 12px 16px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.form-check:hover {
    background: #e9ecef;
}

.form-check-input:checked ~ .form-check-label {
    color: #667eea;
    font-weight: 600;
}

.form-check-input {
    width: 20px;
    height: 20px;
    margin-top: 0;
    border: 2px solid #dee2e6;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    margin-left: 8px;
    cursor: pointer;
    user-select: none;
}

.form-actions {
    display: flex;
    gap: 12px;
}

.btn-lg {
    padding: 14px 32px;
    font-weight: 600;
}

@media (max-width: 576px) {
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
}

@media (min-width: 768px) {
    .form-page {
        padding-bottom: 20px;
        max-width: 800px;
        margin: 0 auto;
    }
}
</style>
@endsection
