@extends('layouts.app')

@section('title', 'Tambah Guru')

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
                <h3 class="mb-1">Tambah Guru Baru</h3>
                <p class="text-muted mb-4">Lengkapi form di bawah untuk menambahkan guru</p>

                <form method="POST" action="{{ route('admin.teachers.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               placeholder="contoh@email.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 6 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-medium">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" 
                                   placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Guru yang ditambahkan akan langsung bisa login dan membuat kursus</small>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-check-circle me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-light btn-lg px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
