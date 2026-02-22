@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="student-dashboard">
    <!-- Header Section -->
    <div class="dashboard-header mb-4">
        <div class="header-content">
            <div class="header-icon">
                <i class="bi bi-book-half"></i>
            </div>
            <div>
                <h2 class="mb-1 fw-bold">Mapel Saya</h2>
                <p class="text-muted mb-0">
                    @if(auth()->user()->classRoom)
                        Kelas {{ auth()->user()->classRoom->name }} - {{ auth()->user()->classRoom->grade }} {{ auth()->user()->classRoom->major }}
                    @else
                        Belum terdaftar di kelas
                    @endif
                </p>
            </div>
        </div>
    </div>

    @if(!auth()->user()->classRoom)
        <!-- No Class Alert -->
        <div class="alert alert-warning mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                <div>
                    <strong>Belum Terdaftar di Kelas</strong>
                    <p class="mb-0 small">Hubungi admin untuk mendaftarkan Anda ke kelas</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    @if($enrolledCourses->count() > 0)
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="bi bi-book"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $enrolledCourses->count() }}</div>
                <div class="stat-label">Mata Pelajaran</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="bi bi-journal-text"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $enrolledCourses->sum('lessons_count') }}</div>
                <div class="stat-label">Total Materi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $enrolledCourses->sum('assignments_count') }}</div>
                <div class="stat-label">Total Tugas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $enrolledCourses->count() }}</div>
                <div class="stat-label">Guru Pengajar</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Courses Grid -->
    <div class="courses-grid">
        @forelse($enrolledCourses as $course)
            <div class="course-card">
                <div class="course-card-header">
                    <div class="course-icon">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <div class="course-badge">
                        <i class="bi bi-person-fill me-1"></i>
                        {{ $course->teacher->name }}
                    </div>
                </div>
                
                <div class="course-card-body">
                    <h5 class="course-title">{{ $course->title }}</h5>
                    <p class="course-description">{{ Str::limit($course->description, 100) ?: 'Tidak ada deskripsi' }}</p>
                    
                    <div class="course-stats">
                        <div class="course-stat-item">
                            <i class="bi bi-journal-text"></i>
                            <span>{{ $course->lessons_count }} Materi</span>
                        </div>
                        <div class="course-stat-item">
                            <i class="bi bi-clipboard-check"></i>
                            <span>{{ $course->assignments_count }} Tugas</span>
                        </div>
                    </div>
                </div>
                
                <div class="course-card-footer">
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-primary w-100">
                        <i class="bi bi-arrow-right-circle me-2"></i>Lihat Materi
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-book"></i>
                </div>
                <h4>Belum Ada Mata Pelajaran</h4>
                @if(auth()->user()->classRoom)
                    <p class="text-muted">Belum ada mata pelajaran yang diajarkan di kelas Anda</p>
                @else
                    <p class="text-muted">Anda belum terdaftar di kelas manapun</p>
                @endif
            </div>
        @endforelse
    </div>
</div>

<style>
.student-dashboard {
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
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
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
    border-top: 1px solid #e9ecef;
}

.course-card-footer .btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 12px 24px;
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

.alert {
    border-radius: 16px;
    border: none;
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
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .courses-grid {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 768px) {
    .student-dashboard {
        padding-bottom: 20px;
    }
}
</style>
@endsection
