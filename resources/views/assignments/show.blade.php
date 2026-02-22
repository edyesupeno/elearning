@extends('layouts.app')

@section('title', $assignment->title)

@section('content')
<div class="detail-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <a href="{{ route('courses.show', $course) }}" class="btn btn-light rounded-circle me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h2 class="mb-1 fw-bold">{{ $assignment->title }}</h2>
            <p class="text-muted mb-0 small">
                <i class="bi bi-book me-1"></i>{{ $course->title }}
            </p>
        </div>
        @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
            <div class="dropdown">
                <button class="btn btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('assignments.edit', [$course, $assignment]) }}">
                        <i class="bi bi-pencil me-2"></i>Edit Tugas
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('assignments.destroy', [$course, $assignment]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-trash me-2"></i>Hapus Tugas
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>

    <!-- Due Date Alert -->
    @if($assignment->due_date)
        <div class="alert {{ $assignment->due_date->isPast() ? 'alert-danger' : 'alert-info' }} mb-3">
            <div class="d-flex align-items-center">
                <i class="bi {{ $assignment->due_date->isPast() ? 'bi-exclamation-triangle-fill' : 'bi-calendar-event' }} me-3 fs-4"></i>
                <div>
                    <strong>{{ $assignment->due_date->isPast() ? 'Batas Waktu Terlewat' : 'Batas Waktu' }}</strong>
                    <p class="mb-0 small">{{ $assignment->due_date->isoFormat('dddd, D MMMM Y [pukul] HH:mm [WIB]') }} ({{ $assignment->due_date->diffForHumans() }})</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Description Card -->
    <div class="info-card mb-3">
        <div class="info-card-header">
            <i class="bi bi-info-circle"></i>
            <h6 class="mb-0">Deskripsi Tugas</h6>
        </div>
        <div class="info-card-body">
            <p class="mb-0">{{ $assignment->description ?: 'Tidak ada deskripsi' }}</p>
        </div>
    </div>

    <!-- Assignment Files -->
    @if($assignment->attachments && count($assignment->attachments) > 0)
    <div class="info-card mb-3">
        <div class="info-card-header">
            <i class="bi bi-paperclip"></i>
            <h6 class="mb-0">File Tugas</h6>
        </div>
        <div class="info-card-body">
            <div class="attachment-list">
                @foreach($assignment->attachments as $index => $attachment)
                    @php
                        $ext = $attachment['type'];
                        $iconClass = 'file-icon ';
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
                    <a href="{{ $canPreview ? '#' : route('assignments.download', [$course, $assignment, $index]) }}" 
                       class="attachment-item"
                       @if($canPreview)
                       onclick="event.preventDefault(); previewFile('{{ route('assignments.preview', [$course, $assignment, $index]) }}', '{{ $attachment['name'] }}', '{{ $ext }}', '{{ route('assignments.download', [$course, $assignment, $index]) }}')"
                       @endif>
                        <div class="{{ $iconClass }}">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div class="file-info flex-grow-1">
                            <div class="file-name">{{ $attachment['name'] }}</div>
                            <div class="file-size">{{ number_format($attachment['size'] / 1024, 2) }} KB</div>
                        </div>
                        <i class="bi {{ $canPreview ? 'bi-eye' : 'bi-download' }}"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Student Submission Section -->
    @if(auth()->user()->isStudent())
        @if($userSubmission)
            <!-- Already Submitted -->
            <div class="info-card mb-3">
                <div class="info-card-header bg-success text-white">
                    <i class="bi bi-check-circle-fill"></i>
                    <h6 class="mb-0">Tugas Sudah Dikumpulkan</h6>
                </div>
                <div class="info-card-body">
                    <p class="text-muted small mb-2">Dikumpulkan pada: {{ $userSubmission->submitted_at->isoFormat('dddd, D MMMM Y [pukul] HH:mm [WIB]') }}</p>
                    
                    @if($userSubmission->notes)
                        <div class="mb-3">
                            <strong>Catatan:</strong>
                            <p class="mb-0">{{ $userSubmission->notes }}</p>
                        </div>
                    @endif

                    @if($userSubmission->attachments && count($userSubmission->attachments) > 0)
                        <strong class="d-block mb-2">File yang Dikumpulkan:</strong>
                        <div class="attachment-list">
                            @foreach($userSubmission->attachments as $index => $attachment)
                                @php
                                    $ext = $attachment['type'];
                                    $iconClass = 'file-icon ';
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
                                <a href="{{ $canPreview ? '#' : route('assignments.submissions.download', [$course, $assignment, $userSubmission, $index]) }}" 
                                   class="attachment-item"
                                   @if($canPreview)
                                   onclick="event.preventDefault(); previewFile('{{ route('assignments.submissions.preview', [$course, $assignment, $userSubmission, $index]) }}', '{{ $attachment['name'] }}', '{{ $ext }}', '{{ route('assignments.submissions.download', [$course, $assignment, $userSubmission, $index]) }}')"
                                   @endif>
                                    <div class="{{ $iconClass }}">
                                        <i class="bi {{ $icon }}"></i>
                                    </div>
                                    <div class="file-info flex-grow-1">
                                        <div class="file-name">{{ $attachment['name'] }}</div>
                                        <div class="file-size">{{ number_format($attachment['size'] / 1024, 2) }} KB</div>
                                    </div>
                                    <i class="bi {{ $canPreview ? 'bi-eye' : 'bi-download' }}"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if($userSubmission->score !== null)
                        <div class="alert alert-success mt-3 mb-0">
                            <strong>Nilai: {{ $userSubmission->score }}</strong>
                            @if($userSubmission->feedback)
                                <p class="mb-0 mt-2"><strong>Feedback:</strong> {{ $userSubmission->feedback }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Submit Form -->
            <div class="info-card mb-3">
                <div class="info-card-header">
                    <i class="bi bi-upload"></i>
                    <h6 class="mb-0">Kumpulkan Tugas</h6>
                </div>
                <div class="info-card-body">
                    <form method="POST" action="{{ route('assignments.submit', [$course, $assignment]) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="4" placeholder="Tambahkan catatan untuk guru...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File Tugas <span class="text-danger">*</span></label>
                            <div class="upload-area" id="uploadArea">
                                <input type="file" name="attachments[]" id="fileInput" class="d-none" multiple required
                                       accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.jpg,.jpeg,.png,.mp4,.mp3">
                                <div class="upload-placeholder" id="uploadPlaceholder">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    <p class="mb-1 fw-semibold">Klik untuk upload file</p>
                                    <p class="text-muted small mb-0">PDF, DOC, PPT, XLS, ZIP, Gambar, Video, Audio (Max 10MB per file)</p>
                                </div>
                                <div id="fileList" class="file-list"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill" id="submitBtn">
                            <i class="bi bi-check-circle me-2"></i>Kumpulkan Tugas
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif

    <!-- Teacher: View All Submissions -->
    @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
        <div class="section-header mb-3">
            <div>
                <h5 class="mb-0 fw-bold">Pengumpulan Siswa</h5>
                <small class="text-muted">{{ $assignment->submissions->count() }} siswa sudah mengumpulkan</small>
            </div>
        </div>

        <div class="submissions-list">
            @forelse($assignment->submissions as $submission)
                <div class="submission-card">
                    <div class="student-info">
                        @if($submission->user->avatar)
                            <img src="{{ asset('storage/' . $submission->user->avatar) }}" alt="{{ $submission->user->name }}" class="student-avatar">
                        @else
                            <div class="student-avatar-placeholder">
                                {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">{{ $submission->user->name }}</h6>
                            <p class="text-muted small mb-0">{{ $submission->submitted_at->isoFormat('D MMM Y, HH:mm') }}</p>
                        </div>
                        @if($submission->score !== null)
                            <span class="badge bg-success">Nilai: {{ $submission->score }}</span>
                        @endif
                    </div>
                    
                    @if($submission->notes)
                        <div class="submission-notes">
                            <strong>Catatan:</strong> {{ $submission->notes }}
                        </div>
                    @endif

                    @if($submission->attachments && count($submission->attachments) > 0)
                        <div class="submission-files">
                            @foreach($submission->attachments as $index => $attachment)
                                @php
                                    $ext = $attachment['type'];
                                    $canPreview = in_array($ext, ['pdf', 'jpg', 'jpeg', 'png']);
                                @endphp
                                <a href="{{ $canPreview ? '#' : route('assignments.submissions.download', [$course, $assignment, $submission, $index]) }}" 
                                   class="file-badge"
                                   @if($canPreview)
                                   onclick="event.preventDefault(); previewFile('{{ route('assignments.submissions.preview', [$course, $assignment, $submission, $index]) }}', '{{ $attachment['name'] }}', '{{ $ext }}', '{{ route('assignments.submissions.download', [$course, $assignment, $submission, $index]) }}')"
                                   @endif>
                                    <i class="bi bi-file-earmark-fill me-1"></i>
                                    {{ Str::limit($attachment['name'], 20) }}
                                    <i class="bi {{ $canPreview ? 'bi-eye' : 'bi-download' }} ms-1"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state-small">
                    <i class="bi bi-inbox text-muted mb-2" style="font-size: 3rem;"></i>
                    <p class="text-muted mb-0">Belum ada siswa yang mengumpulkan tugas</p>
                </div>
            @endforelse
        </div>
    @endif
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

.info-card-header.bg-success {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
}

.info-card-header i {
    font-size: 1.25rem;
    color: #198754;
}

.info-card-header.bg-success i {
    color: white;
}

.info-card-header h6 {
    font-weight: 600;
    color: #1a1a1a;
}

.info-card-header.bg-success h6 {
    color: white;
}

.info-card-body {
    padding: 20px;
}

.info-card-body p {
    color: #6c757d;
    line-height: 1.6;
}

.attachment-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.attachment-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.attachment-item:hover {
    background: #e9ecef;
    transform: translateX(4px);
}

.file-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.file-icon.pdf { background: #dc3545; color: white; }
.file-icon.doc { background: #0d6efd; color: white; }
.file-icon.ppt { background: #fd7e14; color: white; }
.file-icon.xls { background: #198754; color: white; }
.file-icon.zip { background: #6c757d; color: white; }
.file-icon.img { background: #0dcaf0; color: white; }
.file-icon.video { background: #d63384; color: white; }
.file-icon.audio { background: #6610f2; color: white; }

.file-info {
    flex-grow: 1;
    min-width: 0;
}

.file-name {
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.file-size {
    font-size: 0.75rem;
    color: #6c757d;
}

.upload-area {
    border: 2px dashed #e9ecef;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #198754;
    background: #f8f9fa;
}

.upload-area.has-files {
    border-style: solid;
    border-color: #198754;
    background: #f8f9fa;
}

.upload-icon {
    font-size: 3rem;
    color: #198754;
    margin-bottom: 12px;
}

.file-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 16px;
}

.file-list .file-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.file-remove {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: #dc3545;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.file-remove:hover {
    background: #bb2d3b;
}

.form-control {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.submissions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.submission-card {
    background: white;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.student-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.student-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
}

.student-avatar-placeholder {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    font-weight: 700;
    flex-shrink: 0;
}

.submission-notes {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 12px;
    font-size: 0.9rem;
}

.submission-files {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.file-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    color: white;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.file-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.4);
    color: white;
}

.empty-state-small {
    text-align: center;
    padding: 40px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.alert {
    border-radius: 16px;
    border: none;
}

@media (max-width: 576px) {
    .page-header h2 {
        font-size: 1.5rem;
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

document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const fileList = document.getElementById('fileList');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!uploadArea) return;
    
    let selectedFiles = [];

    uploadArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        files.forEach(file => {
            if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        });
        updateFileList();
    });

    function updateFileList() {
        if (selectedFiles.length > 0) {
            uploadArea.classList.add('has-files');
            uploadPlaceholder.style.display = 'none';
            fileList.style.display = 'block';
            fileList.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileItem = createFileItem(file, index);
                fileList.appendChild(fileItem);
            });
        } else {
            uploadArea.classList.remove('has-files');
            uploadPlaceholder.style.display = 'block';
            fileList.style.display = 'none';
        }
    }

    function createFileItem(file, index) {
        const div = document.createElement('div');
        div.className = 'file-item';

        const ext = file.name.split('.').pop().toLowerCase();
        let iconClass = 'file-icon ';
        let icon = 'bi-file-earmark';

        if (['pdf'].includes(ext)) {
            iconClass += 'pdf';
            icon = 'bi-file-pdf';
        } else if (['doc', 'docx'].includes(ext)) {
            iconClass += 'doc';
            icon = 'bi-file-word';
        } else if (['ppt', 'pptx'].includes(ext)) {
            iconClass += 'ppt';
            icon = 'bi-file-ppt';
        } else if (['xls', 'xlsx'].includes(ext)) {
            iconClass += 'xls';
            icon = 'bi-file-excel';
        } else if (['zip', 'rar'].includes(ext)) {
            iconClass += 'zip';
            icon = 'bi-file-zip';
        } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
            iconClass += 'img';
            icon = 'bi-file-image';
        } else if (['mp4'].includes(ext)) {
            iconClass += 'video';
            icon = 'bi-camera-video';
        } else if (['mp3'].includes(ext)) {
            iconClass += 'audio';
            icon = 'bi-music-note';
        }

        div.innerHTML = `
            <div class="${iconClass}">
                <i class="bi ${icon}"></i>
            </div>
            <div class="file-info">
                <div class="file-name">${file.name}</div>
                <div class="file-size">${formatFileSize(file.size)}</div>
            </div>
            <button type="button" class="file-remove" data-index="${index}">
                <i class="bi bi-x-lg"></i>
            </button>
        `;

        div.querySelector('.file-remove').addEventListener('click', function(e) {
            e.stopPropagation();
            selectedFiles.splice(index, 1);
            updateFileList();
            updateFileInput();
        });

        return div;
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Form submission
    const form = document.querySelector('form');
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengumpulkan...';
        });
    }
});
</script>
@endsection
