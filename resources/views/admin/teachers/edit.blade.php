@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-4">
            <a href="{{ route('admin.teachers.index') }}" class="text-decoration-none text-muted">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-1">Edit Data Guru</h3>
                <p class="text-muted mb-4">Update informasi guru</p>

                <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               value="{{ old('name', $teacher->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               value="{{ old('email', $teacher->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-medium">Password Baru</label>
                            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   placeholder="Kosongkan jika tidak ingin mengubah">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-medium">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" 
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small>Jika password diubah, guru harus login ulang dengan password baru</small>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-check-circle me-2"></i>Update
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-light btn-lg px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body p-4">
                <h5 class="mb-3">Informasi Tambahan</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-2">Total Kursus</p>
                        <h4>{{ $teacher->courses->count() }} Kursus</h4>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-2">Bergabung Sejak</p>
                        <h4>{{ $teacher->created_at->format('d M Y') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
