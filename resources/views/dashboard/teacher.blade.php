@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="teacher-dashboard">
    <!-- Header Section -->
    <div class="dashboard-header mb-4">
        <div class="header-content">
            <div class="header-icon">
                <i class="bi bi-book-half"></i>
            </div>
            <div>
                <h2 class="mb-1 fw-bold">Mapel Saya</h2>
                <p class="text-muted mb-0">Kelola mata pelajaran yang Anda ajarkan</p>
            </div>
        </div>
        <a href="{{ route('courses.create') }}" class="btn btn-primary btn-create">
            <i class="bi bi-plus-circle me-2"></i>Buat Mapel Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="bi bi-book"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $courses->count() }}</div>
                <div class="stat-label">Total Mapel</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $courses->sum('enrollments_count') }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="bi bi-journal-text"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $courses->sum(function($c) { return $c->lessons->count(); }) }}</div>
                <div class="stat-label">Total Materi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $courses->sum(function($c) { return $c->assignments->count(); }) }}</div>
                <div class="stat-label">Total Tugas</div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="courses-grid">
        @forelse($courses as $course)
            <div class="course-card">
                <div class="course-card-header">
                    <div class="course-icon">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <div class="course-badge">
                        <i class="bi bi-people-fill me-1"></i>
                        {{ $course->enrollments_count }} siswa
                    </div>
                </div>
                
                <div class="course-card-body">
                    <h5 class="course-title">{{ $course->title }}</h5>
                    <p class="course-description">{{ Str::limit($course->description, 100) ?: 'Tidak ada deskripsi' }}</p>
                    
                    <div class="course-stats">
                        <div class="course-stat-item">
                            <i class="bi bi-journal-text"></i>
                            <span>{{ $course->lessons->count() }} Materi</span>
                        </div>
                        <div class="course-stat-item">
                            <i class="bi bi-clipboard-check"></i>
                            <span>{{ $course->assignments->count() }} Tugas</span>
                        </div>
                        @if($course->classes->count() > 0)
                            <div class="course-stat-item">
                                <i class="bi bi-door-open"></i>
                                <span>{{ $course->classes->count() }} Kelas</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="course-card-footer">
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-book"></i>
                </div>
                <h4>Belum Ada Mata Pelajaran</h4>
                <p class="text-muted">Mulai dengan membuat mata pelajaran pertama Anda</p>
                <a href="{{ route('courses.create') }}" class="btn btn-primary btn-lg rounded-pill mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Buat Mapel Pertama
                </a>
            </div>
        @endforelse
    </div>
</div>

<style>
.teacher-dashboard {
    padding-bottom: 100px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.75rem;
}

.header-content h2 {
    color: #1a1a1a;
}

.btn-create {
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
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

.stat-info {
    flex-grow: 1;
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

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

.course-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.course-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
}

.course-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.course-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.course-badge {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.course-card-body {
    padding: 24px;
    flex-grow: 1;
}

.course-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
    line-height: 1.4;
}

.course-description {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 16px;
}

.course-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.course-stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 0.875rem;
    color: #495057;
}

.course-stat-item i {
    color: #667eea;
}

.course-card-footer {
    padding: 16px 24px;
    background: #f8f9fa;
    display: flex;
    gap: 8px;
    border-top: 1px solid #e9ecef;
}

.course-card-footer .btn {
    flex: 1;
    border-radius: 10px;
    font-weight: 600;
    padding: 8px 16px;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    color: white;
    font-size: 4rem;
}

.empty-state h4 {
    color: #1a1a1a;
    margin-bottom: 8px;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .btn-create {
        width: 100%;
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .courses-grid {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 768px) {
    .teacher-dashboard {
        padding-bottom: 20px;
    }
}
</style>
@endsection
