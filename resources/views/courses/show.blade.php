@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="detail-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <a href="{{ route('courses.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h2 class="mb-1 fw-bold">{{ $course->title }}</h2>
            <p class="text-muted mb-0 small">
                <i class="bi bi-person me-1"></i>{{ $course->teacher->name }}
            </p>
        </div>
        @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
            <div class="dropdown">
                <button class="btn btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('courses.edit', $course) }}">
                        <i class="bi bi-pencil me-2"></i>Edit Mapel
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-trash me-2"></i>Hapus Mapel
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>

    <!-- Description Card -->
    <div class="info-card mb-3">
        <div class="info-card-header">
            <i class="bi bi-info-circle"></i>
            <h6 class="mb-0">Deskripsi</h6>
        </div>
        <div class="info-card-body">
            <p class="mb-0">{{ $course->description ?: 'Tidak ada deskripsi' }}</p>
        </div>
    </div>

    <!-- Classes Card -->
    @if($course->classes->count() > 0)
    <div class="info-card mb-3">
        <div class="info-card-header">
            <i class="bi bi-door-open"></i>
            <h6 class="mb-0">Kelas yang Mengikuti</h6>
        </div>
        <div class="info-card-body">
            <div class="class-badges">
                @foreach($course->classes as $class)
                    <span class="badge-class">
                        <i class="bi bi-mortarboard-fill me-1"></i>
                        {{ $class->name }} - Kelas {{ $class->grade }} {{ $class->major }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Card -->
    <div class="stats-grid mb-4">
        <div class="stat-box">
            <div class="stat-icon bg-primary">
                <i class="bi bi-journal-text"></i>
            </div>
            <div>
                <div class="stat-value">{{ $course->lessons->count() }}</div>
                <div class="stat-label">Materi</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon bg-success">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="stat-value">{{ $course->enrollments->count() }}</div>
                <div class="stat-label">Siswa</div>
            </div>
        </div>
    </div>

    <!-- Enroll Button for Students -->
    @if(auth()->user()->isStudent() && !$isEnrolled)
        <div class="alert alert-info mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong>Belum terdaftar</strong>
                    <p class="mb-0 small">Daftar untuk mengakses semua materi pembelajaran</p>
                </div>
            </div>
            <form action="{{ route('courses.enroll', $course) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-success w-100 rounded-pill">
                    <i class="bi bi-check-circle me-2"></i>Daftar Sekarang
                </button>
            </form>
        </div>
    @endif

    <!-- Lessons Section -->
    <div class="section-header mb-3">
        <div>
            <h5 class="mb-0 fw-bold">Materi Pembelajaran</h5>
            <small class="text-muted">{{ $course->lessons->count() }} materi tersedia</small>
        </div>
        @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
            <a href="{{ route('lessons.create', $course) }}" class="btn btn-primary btn-sm rounded-pill">
                <i class="bi bi-plus-lg me-1"></i>Tambah
            </a>
        @endif
    </div>

    <div class="lessons-list mb-4">
        @forelse($course->lessons as $lesson)
            <div class="lesson-card">
                <div class="lesson-number">{{ $lesson->order }}</div>
                <div class="lesson-content flex-grow-1">
                    <h6 class="mb-1 fw-semibold">{{ $lesson->title }}</h6>
                    <p class="text-muted small mb-2">{{ Str::limit($lesson->content, 120) }}</p>
                    
                    @if($lesson->attachments && count($lesson->attachments) > 0)
                        <div class="lesson-attachments">
                            @foreach($lesson->attachments as $index => $attachment)
                                @php
                                    $ext = $attachment['type'];
                                    $iconClass = 'attachment-icon ';
                                    $icon = 'bi-file-earmark';
                                    $canPreview = in_array($ext, ['pdf', 'jpg', 'jpeg', 'png']);
                                    
                                    if ($ext == 'pdf') {
                                        $iconClass .= 'pdf';
                                        $icon = 'bi-file-pdf-fill';
                                    } elseif (in_array($ext, ['doc', 'docx'])) {
                                        $iconClass .= 'doc';
                                        $icon = 'bi-file-word-fill';
                                    } elseif (in_array($ext, ['ppt', 'pptx'])) {
                                        $iconClass .= 'ppt';
                                        $icon = 'bi-file-ppt-fill';
                                    } elseif (in_array($ext, ['xls', 'xlsx'])) {
                                        $iconClass .= 'xls';
                                        $icon = 'bi-file-excel-fill';
                                    } elseif (in_array($ext, ['zip', 'rar'])) {
                                        $iconClass .= 'zip';
                                        $icon = 'bi-file-zip-fill';
                                    } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                                        $iconClass .= 'img';
                                        $icon = 'bi-file-image-fill';
                                    } elseif ($ext == 'mp4') {
                                        $iconClass .= 'video';
                                        $icon = 'bi-camera-video-fill';
                                    } elseif ($ext == 'mp3') {
                                        $iconClass .= 'audio';
                                        $icon = 'bi-music-note-beamed';
                                    }
                                @endphp
                                <a href="{{ $canPreview ? '#' : route('lessons.download', [$course, $lesson, $index]) }}" 
                                   class="attachment-badge" 
                                   title="{{ $attachment['name'] }}"
                                   @if($canPreview)
                                   onclick="event.preventDefault(); previewFile('{{ route('lessons.preview', [$course, $lesson, $index]) }}', '{{ $attachment['name'] }}', '{{ $ext }}', '{{ route('lessons.download', [$course, $lesson, $index]) }}')"
                                   @endif>
                                    <i class="bi {{ $icon }} me-1"></i>
                                    <span class="attachment-name">{{ Str::limit($attachment['name'], 20) }}</span>
                                    <i class="bi {{ $canPreview ? 'bi-eye' : 'bi-download' }} ms-1"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
                    <div class="lesson-actions">
                        <a href="{{ route('lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('lessons.destroy', [$course, $lesson]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus materi ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state-small">
                <i class="bi bi-journal-x text-muted mb-2" style="font-size: 3rem;"></i>
                <p class="text-muted mb-0">Belum ada materi pembelajaran</p>
                @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
                    <a href="{{ route('lessons.create', $course) }}" class="btn btn-primary btn-sm rounded-pill mt-3">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Materi Pertama
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Assignments Section -->
    <div class="section-header mb-3">
        <div>
            <h5 class="mb-0 fw-bold">Tugas</h5>
            <small class="text-muted">{{ $course->assignments->count() }} tugas tersedia</small>
        </div>
        @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
            <a href="{{ route('assignments.create', $course) }}" class="btn btn-success btn-sm rounded-pill">
                <i class="bi bi-plus-lg me-1"></i>Tambah
            </a>
        @endif
    </div>

    <div class="assignments-list">
        @forelse($course->assignments as $assignment)
            <a href="{{ route('assignments.show', [$course, $assignment]) }}" class="assignment-card">
                <div class="assignment-icon">
                    <i class="bi bi-clipboard-check-fill"></i>
                </div>
                <div class="assignment-content flex-grow-1">
                    <h6 class="mb-1 fw-semibold">{{ $assignment->title }}</h6>
                    <p class="text-muted small mb-2">{{ Str::limit($assignment->description, 100) }}</p>
                    
                    <div class="assignment-meta">
                        @if($assignment->due_date)
                            <span class="meta-badge {{ $assignment->due_date->isPast() ? 'overdue' : 'active' }}">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $assignment->due_date->isoFormat('D MMMM Y, HH:mm') }}
                            </span>
                        @endif
                        @if($assignment->attachments && count($assignment->attachments) > 0)
                            <span class="meta-badge">
                                <i class="bi bi-paperclip me-1"></i>
                                {{ count($assignment->attachments) }} file
                            </span>
                        @endif
                        @if(auth()->user()->isStudent())
                            @php
                                $submission = $assignment->getSubmissionByUser(auth()->id());
                            @endphp
                            @if($submission)
                                <span class="meta-badge submitted">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Sudah dikumpulkan
                                </span>
                            @else
                                <span class="meta-badge pending">
                                    <i class="bi bi-clock me-1"></i>
                                    Belum dikumpulkan
                                </span>
                            @endif
                        @endif
                        @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
                            <span class="meta-badge">
                                <i class="bi bi-people me-1"></i>
                                {{ $assignment->submissions->count() }} pengumpulan
                            </span>
                        @endif
                    </div>
                </div>
                <div class="assignment-arrow">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
        @empty
            <div class="empty-state-small">
                <i class="bi bi-clipboard-x text-muted mb-2" style="font-size: 3rem;"></i>
                <p class="text-muted mb-0">Belum ada tugas</p>
                @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
                    <a href="{{ route('assignments.create', $course) }}" class="btn btn-success btn-sm rounded-pill mt-3">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Tugas Pertama
                    </a>
                @endif
            </div>
        @endforelse
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent" style="min-height: 500px;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="downloadBtn" class="btn btn-primary" download>
                    <i class="bi bi-download me-2"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
.detail-page {
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

.info-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.info-card-header {
    padding: 16px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.info-card-header i {
    font-size: 1.25rem;
    color: #667eea;
}

.info-card-header h6 {
    font-weight: 600;
    color: #1a1a1a;
}

.info-card-body {
    padding: 20px;
}

.info-card-body p {
    color: #6c757d;
    line-height: 1.6;
}

.class-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.badge-class {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.stat-box {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 4px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.lessons-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.lesson-card {
    background: white;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
}

.lesson-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.lesson-number {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.lesson-content h6 {
    color: #1a1a1a;
}

.lesson-attachments {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}

.attachment-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
}

.attachment-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

.attachment-name {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.lesson-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.lesson-actions .btn {
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

.empty-state-small {
    text-align: center;
    padding: 40px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.assignments-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.assignment-card {
    background: white;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    border-left: 4px solid #198754;
}

.assignment-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.assignment-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.assignment-content h6 {
    color: #1a1a1a;
}

.assignment-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    background: #e9ecef;
    color: #6c757d;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.meta-badge.active {
    background: #d1e7dd;
    color: #0f5132;
}

.meta-badge.overdue {
    background: #f8d7da;
    color: #842029;
}

.meta-badge.submitted {
    background: #d1e7dd;
    color: #0f5132;
}

.meta-badge.pending {
    background: #fff3cd;
    color: #664d03;
}

.assignment-arrow {
    color: #6c757d;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.alert {
    border-radius: 16px;
    border: none;
}

@media (max-width: 576px) {
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .lesson-actions {
        flex-direction: column;
    }
}

@media (min-width: 768px) {
    .detail-page {
        padding-bottom: 20px;
        max-width: 900px;
        margin: 0 auto;
    }
}

#previewContent img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}

#previewContent iframe {
    width: 100%;
    height: 600px;
    border: none;
}
</style>

<script>
function previewFile(previewUrl, fileName, fileType, downloadUrl) {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    const previewContent = document.getElementById('previewContent');
    const modalLabel = document.getElementById('previewModalLabel');
    const downloadBtn = document.getElementById('downloadBtn');
    
    modalLabel.textContent = fileName;
    downloadBtn.href = downloadUrl;
    
    // Show loading
    previewContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Load preview based on file type
    if (['jpg', 'jpeg', 'png'].includes(fileType)) {
        previewContent.innerHTML = `<img src="${previewUrl}" alt="${fileName}" class="img-fluid">`;
    } else if (fileType === 'pdf') {
        previewContent.innerHTML = `<iframe src="${previewUrl}" type="application/pdf"></iframe>`;
    }
}
</script>
@endsection
