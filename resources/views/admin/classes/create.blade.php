@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="form-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <a href="{{ route('admin.classes.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="mb-1 fw-bold">Tambah Kelas Baru</h2>
            <p class="text-muted mb-0 small">Buat kelas atau rombongan belajar baru</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.classes.store') }}">
        @csrf
        
        <div class="form-card mb-3">
            <div class="form-card-header">
                <i class="bi bi-door-open"></i>
                <h6 class="mb-0">Informasi Kelas</h6>
            </div>
            <div class="form-card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           placeholder="Contoh: 10A, 11 IPA 1, XII Bahasa" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tingkat</label>
                        <input type="text" name="grade" class="form-control @error('grade') is-invalid @enderror" 
                               value="{{ old('grade') }}" 
                               placeholder="Contoh: 10, 11, 12">
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Opsional</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jurusan</label>
                        <input type="text" name="major" class="form-control @error('major') is-invalid @enderror" 
                               value="{{ old('major') }}" 
                               placeholder="Contoh: IPA, IPS, Bahasa">
                        @error('major')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Opsional</small>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="4" placeholder="Keterangan tambahan tentang kelas...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Opsional - Berikan keterangan tambahan jika diperlukan</small>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill flex-fill">
                <i class="bi bi-check-circle me-2"></i>Simpan
            </button>
            <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill flex-fill">
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

.form-card-header {
    padding: 16px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.form-card-header i {
    font-size: 1.25rem;
    color: #667eea;
}

.form-card-header h6 {
    font-weight: 600;
    color: #1a1a1a;
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
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
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
