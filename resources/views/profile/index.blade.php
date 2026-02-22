@extends('layouts.app')

@section('title', 'Setting')

@section('content')
<div class="setting-page">
    <div class="profile-header text-center mb-4">
        <div class="avatar-wrapper position-relative d-inline-block mb-3">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-image">
            @else
                <div class="avatar-circle bg-gradient-primary text-white" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <button type="button" class="avatar-edit-btn" data-bs-toggle="modal" data-bs-target="#avatarModal">
                <i class="bi bi-camera"></i>
            </button>
        </div>
        <h3 class="mb-1 fw-bold">{{ $user->name }}</h3>
        <p class="text-muted mb-2">{{ $user->email }}</p>
        <span class="badge rounded-pill bg-primary px-3 py-2">
            <i class="bi bi-person-badge me-1"></i>{{ ucfirst($user->role->name) }}
        </span>
    </div>

    <!-- Menu Cards -->
    @if($user->isAdmin())
    <div class="mb-4">
        <h6 class="text-muted text-uppercase small fw-bold mb-3 px-2">Menu Admin</h6>
        <div class="menu-card mb-2">
            <a href="{{ route('admin.teachers.index') }}" class="menu-item">
                <div class="menu-icon bg-primary">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div class="menu-content">
                    <h6 class="mb-0">Kelola Guru</h6>
                    <small class="text-muted">Tambah, edit, hapus data guru</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
        <div class="menu-card mb-2">
            <a href="{{ route('admin.classes.index') }}" class="menu-item">
                <div class="menu-icon bg-info">
                    <i class="bi bi-door-open"></i>
                </div>
                <div class="menu-content">
                    <h6 class="mb-0">Kelola Kelas</h6>
                    <small class="text-muted">Manajemen kelas dan rombel</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
        <div class="menu-card mb-2">
            <a href="{{ route('admin.students.index') }}" class="menu-item">
                <div class="menu-icon bg-warning">
                    <i class="bi bi-people"></i>
                </div>
                <div class="menu-content">
                    <h6 class="mb-0">Kelola Siswa</h6>
                    <small class="text-muted">Tambah, edit, hapus data siswa</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
        <div class="menu-card mb-2">
            <a href="{{ route('admin.settings.index') }}" class="menu-item">
                <div class="menu-icon bg-success">
                    <i class="bi bi-gear"></i>
                </div>
                <div class="menu-content">
                    <h6 class="mb-0">Pengaturan Aplikasi</h6>
                    <small class="text-muted">Logo, favicon, info aplikasi</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>
    @endif

    @if($user->isTeacher())
    <div class="mb-4">
        <h6 class="text-muted text-uppercase small fw-bold mb-3 px-2">Statistik</h6>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="bi bi-book"></i>
            </div>
            <div class="stat-content">
                <small class="text-muted d-block">Total Mapel</small>
                <h3 class="mb-0 fw-bold">{{ $user->courses->count() }}</h3>
            </div>
        </div>
    </div>
    @endif

    @if($user->isStudent())
    <div class="mb-4">
        <h6 class="text-muted text-uppercase small fw-bold mb-3 px-2">Statistik</h6>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="bi bi-book"></i>
            </div>
            <div class="stat-content">
                <small class="text-muted d-block">Mapel Diikuti</small>
                <h3 class="mb-0 fw-bold">{{ $user->enrollments->count() }}</h3>
            </div>
        </div>
    </div>
    @endif

    <!-- General Menu -->
    <div class="mb-4">
        <h6 class="text-muted text-uppercase small fw-bold mb-3 px-2">Umum</h6>
        <div class="menu-card mb-2">
            <a href="{{ route('dashboard') }}" class="menu-item">
                <div class="menu-icon bg-primary">
                    <i class="bi bi-house-door"></i>
                </div>
                <div class="menu-content">
                    <h6 class="mb-0">Dashboard</h6>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
        <div class="menu-card mb-2">
            <a href="{{ route('courses.index') }}" class="menu-item">
                <div class="menu-icon bg-success">
                    <i class="bi bi-book"></i>
                </div>
                <div class="menu-content">
                    <h6 class="mb-0">Mapel</h6>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Logout -->
    <div class="menu-card logout-card mb-5">
        <form action="{{ route('logout') }}" method="POST" class="w-100">
            @csrf
            <button type="submit" class="menu-item logout-btn">
                <div class="menu-icon bg-danger">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <div class="menu-content">
                    <h6 class="mb-0 text-danger">Logout</h6>
                </div>
            </button>
        </form>
    </div>
</div>

<!-- Avatar Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($user->avatar)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                    <form action="{{ route('profile.avatar.delete') }}" method="POST" class="mb-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Hapus foto profil?')">
                            <i class="bi bi-trash me-2"></i>Hapus Foto
                        </button>
                    </form>
                    <hr>
                @endif
                
                <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload Foto Baru</label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*" required>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-upload me-2"></i>Upload Foto
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.setting-page {
    padding-bottom: 100px;
}

.avatar-wrapper {
    position: relative;
}

.avatar-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
}

.avatar-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
}

.avatar-edit-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    border: 2px solid #0d6efd;
    color: #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.avatar-edit-btn:hover {
    background: #0d6efd;
    color: white;
    transform: scale(1.1);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.menu-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.menu-card:active {
    transform: scale(0.98);
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 16px;
    text-decoration: none;
    color: inherit;
    transition: background 0.2s ease;
}

.menu-item:hover {
    background: rgba(0, 0, 0, 0.02);
}

.menu-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
    margin-right: 16px;
}

.menu-content {
    flex-grow: 1;
    min-width: 0;
}

.menu-content h6 {
    font-weight: 600;
    color: #1a1a1a;
}

.menu-content small {
    font-size: 0.8rem;
}

.logout-btn {
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.logout-card {
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.stat-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    padding: 20px;
    display: flex;
    align-items: center;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    margin-right: 16px;
}

.stat-content {
    flex-grow: 1;
}

.badge.rounded-pill {
    font-size: 0.85rem;
    font-weight: 500;
}

@media (max-width: 576px) {
    .avatar-image,
    .avatar-circle {
        width: 90px !important;
        height: 90px !important;
        font-size: 2.25rem !important;
    }
    
    h3 {
        font-size: 1.5rem;
    }
}

@media (min-width: 768px) {
    .setting-page {
        padding-bottom: 20px;
    }
}
</style>
@endsection
