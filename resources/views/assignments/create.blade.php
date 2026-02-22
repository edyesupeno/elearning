@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')
<div class="form-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <a href="{{ route('courses.show', $course) }}" class="btn btn-light rounded-circle me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="mb-1 fw-bold">Tambah Tugas</h2>
            <p class="text-muted mb-0 small">{{ $course->title }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('assignments.store', $course) }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-card mb-3">
            <div class="form-card-body">
                <label class="form-label fw-semibold">Judul Tugas <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                       value="{{ old('title') }}" 
                       placeholder="Contoh: Tugas Membuat Esai" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-card mb-3">
            <div class="form-card-body">
                <label class="form-label fw-semibold">Urutan <span class="text-danger">*</span></label>
                <input type="number" name="order" class="form-control form-control-lg @error('order') is-invalid @enderror" 
                       value="{{ old('order', $course->assignments->count() + 1) }}" 
                       min="1" required>
                @error('order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Urutan tampilan tugas</small>
            </div>
        </div>

        <div class="form-card mb-3">
            <div class="form-card-body">
                <label class="form-label fw-semibold">Batas Waktu</label>
                <input type="datetime-local" name="due_date" class="form-control form-control-lg @error('due_date') is-invalid @enderror" 
                       value="{{ old('due_date') }}">
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Opsional - Tentukan batas waktu pengumpulan</small>
            </div>
        </div>

        <div class="form-card mb-3">
            <div class="form-card-body">
                <label class="form-label fw-semibold">Deskripsi Tugas</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="6" placeholder="Jelaskan tugas yang harus dikerjakan siswa...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-card mb-4">
            <div class="form-card-body">
                <label class="form-label fw-semibold">File Tugas</label>
                <div class="upload-area" id="uploadArea">
                    <input type="file" name="attachments[]" id="fileInput" class="d-none" multiple 
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.jpg,.jpeg,.png,.mp4,.mp3">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <i class="bi bi-cloud-upload upload-icon"></i>
                        <p class="mb-1 fw-semibold">Klik untuk upload file</p>
                        <p class="text-muted small mb-0">PDF, DOC, PPT, XLS, ZIP, Gambar, Video, Audio (Max 10MB per file)</p>
                    </div>
                    <div id="fileList" class="file-list"></div>
                </div>
                <small class="text-muted">Opsional - Upload file pendukung tugas (bisa lebih dari 1 file)</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success btn-lg rounded-pill flex-fill" id="submitBtn">
                <i class="bi bi-check-circle me-2"></i>Simpan
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

.form-control-lg {
    padding: 14px 16px;
    font-size: 1rem;
    border-radius: 12px;
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

.upload-area {
    border: 2px dashed #e9ecef;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 8px;
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

.file-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 10px;
    border: 1px solid #e9ecef;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const fileList = document.getElementById('fileList');
    const submitBtn = document.getElementById('submitBtn');
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
    document.querySelector('form').addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });
});
</script>
@endsection
