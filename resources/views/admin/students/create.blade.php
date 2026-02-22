@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<div class="form-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <a href="{{ route('admin.students.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="mb-1 fw-bold">Tambah Siswa Baru</h2>
            <p class="text-muted mb-0 small">Buat akun siswa baru</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.students.store') }}">
        @csrf
        
        <div class="form-card mb-3">
            <div class="form-card-header">
                <i class="bi bi-person"></i>
                <h6 class="mb-0">Informasi Siswa</h6>
            </div>
            <div class="form-card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           placeholder="Masukkan nama lengkap siswa" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           placeholder="contoh@email.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Email akan digunakan untuk login</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Minimal 6 karakter" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Password default untuk siswa</small>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold">Kelas</label>
                    <select name="class_id" class="form-select @error('class_id') is-invalid @enderror">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}@if($class->grade || $class->major) - Kelas {{ $class->grade }} {{ $class->major }}@endif
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Opsional - Siswa bisa tanpa kelas</small>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill flex-fill">
                <i class="bi bi-check-circle me-2"></i>Simpan
            </button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill flex-fill">
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

.form-control, .form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 12px 16px;
    font-size: 1rem;
    line-height: 1.5;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    outline: none;
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px 12px;
    padding-right: 40px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}

.form-select option {
    padding: 10px 16px;
    font-size: 1rem;
    line-height: 1.6;
    color: #1a1a1a;
    background: white;
}

.form-select option:checked {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
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
