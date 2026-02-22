@extends('layouts.app')

@section('title', $class->name)

@section('content')
<div class="detail-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <a href="{{ route('admin.classes.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h2 class="mb-1 fw-bold">{{ $class->name }}</h2>
            <p class="text-muted mb-0 small">
                @if($class->grade || $class->major)
                    <span class="badge bg-primary me-1">Kelas {{ $class->grade }}</span>
                    @if($class->major)
                        <span class="badge bg-info">{{ $class->major }}</span>
                    @endif
                @endif
            </p>
        </div>
        <div class="dropdown">
            <button class="btn btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.classes.edit', $class) }}">
                    <i class="bi bi-pencil me-2"></i>Edit Kelas
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-trash me-2"></i>Hapus Kelas
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Description Card -->
    @if($class->description)
    <div class="info-card mb-3">
        <div class="info-card-header">
            <i class="bi bi-info-circle"></i>
            <h6 class="mb-0">Deskripsi</h6>
        </div>
        <div class="info-card-body">
            <p class="mb-0">{{ $class->description }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Card -->
    <div class="stats-grid mb-4">
        <div class="stat-box">
            <div class="stat-icon bg-primary">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="stat-value">{{ $class->students->count() }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon bg-info">
                <i class="bi bi-book"></i>
            </div>
            <div>
                <div class="stat-value">{{ $class->courses->count() }}</div>
                <div class="stat-label">Mata Pelajaran</div>
            </div>
        </div>
    </div>

    <!-- Courses Section -->
    @if($class->courses->count() > 0)
    <div class="section-header mb-3">
        <div>
            <h5 class="mb-0 fw-bold">Mata Pelajaran</h5>
            <small class="text-muted">{{ $class->courses->count() }} mapel tersedia</small>
        </div>
    </div>

    <div class="courses-list mb-4">
        @foreach($class->courses as $course)
            <a href="{{ route('courses.show', $course) }}" class="course-item">
                <div class="course-icon">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="course-info flex-grow-1">
                    <h6 class="mb-0 fw-semibold">{{ $course->title }}</h6>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-person me-1"></i>{{ $course->teacher->name }}
                    </p>
                </div>
                <div class="course-arrow">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
        @endforeach
    </div>
    @endif

    <!-- Students Section -->
    <div class="section-header mb-3">
        <div>
            <h5 class="mb-0 fw-bold">Daftar Siswa</h5>
            <small class="text-muted">{{ $class->students->count() }} siswa terdaftar</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm btn-add" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="bi bi-plus-lg"></i>
            <span class="ms-2">Tambah</span>
        </button>
    </div>

    <!-- Search Bar -->
    <div class="search-wrapper mb-3">
        <i class="bi bi-search search-icon"></i>
        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari siswa...">
        <button class="btn-clear" id="clearSearch" style="display: none;">
            <i class="bi bi-x-circle-fill"></i>
        </button>
    </div>

    <div class="students-list" id="studentsList">
        @forelse($class->students as $student)
            <div class="student-item" data-name="{{ strtolower($student->name) }}" data-email="{{ strtolower($student->email) }}">
                <div class="student-info">
                    @if($student->avatar)
                        <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->name }}" class="student-avatar-small">
                    @else
                        <div class="student-avatar-small-placeholder">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="mb-0 fw-semibold text-truncate">{{ $student->name }}</h6>
                        <p class="text-muted small mb-0 text-truncate">
                            <i class="bi bi-envelope me-1"></i>{{ $student->email }}
                        </p>
                    </div>
                </div>
                <div class="student-actions-small">
                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.classes.removeStudent', [$class, $student]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus siswa dari kelas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state-small" id="emptyState">
                <i class="bi bi-people text-muted mb-2" style="font-size: 3rem;"></i>
                <p class="text-muted mb-0">Belum ada siswa di kelas ini</p>
                <button type="button" class="btn btn-primary btn-sm rounded-pill mt-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Siswa
                </button>
            </div>
        @endforelse
    </div>

    <!-- No Results State -->
    <div class="empty-state-small" id="noResults" style="display: none;">
        <i class="bi bi-search text-muted mb-2" style="font-size: 3rem;"></i>
        <p class="text-muted mb-0">Tidak ditemukan siswa dengan kata kunci tersebut</p>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid #e9ecef; padding: 20px 24px;">
                <h5 class="modal-title fw-bold" id="addStudentModalLabel">
                    <i class="bi bi-person-plus me-2" style="color: #667eea;"></i>Tambah Siswa ke Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.classes.addStudent', $class) }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 24px;">
                    @if($availableStudents->count() > 0)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Siswa</label>
                            <select name="student_id" class="form-select" required style="border-radius: 12px; padding: 12px 16px; border: 2px solid #e9ecef;">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($availableStudents as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->name }} - {{ $student->email }}
                                        @if($student->class_id)
                                            (Kelas: {{ $student->class->name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i>
                                Siswa yang sudah ada di kelas lain akan dipindahkan ke kelas ini
                            </small>
                        </div>
                    @else
                        <div class="alert alert-info" style="border-radius: 12px; border: none;">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Tidak ada siswa yang tersedia. Semua siswa sudah terdaftar di kelas ini atau belum ada siswa yang dibuat.
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 16px 24px;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 12px; padding: 10px 20px;">Batal</button>
                    @if($availableStudents->count() > 0)
                        <button type="submit" class="btn btn-primary" style="border-radius: 12px; padding: 10px 20px;">
                            <i class="bi bi-check-lg me-1"></i>Tambahkan
                        </button>
                    @endif
                </div>
            </form>
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.courses-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.course-item {
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
}

.course-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.course-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.course-info h6 {
    color: #1a1a1a;
}

.course-arrow {
    color: #6c757d;
    font-size: 1.25rem;
    flex-shrink: 0;
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

.btn-add {
    padding: 8px 16px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
}

.search-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1rem;
    pointer-events: none;
    z-index: 2;
}

.search-input {
    padding: 12px 48px 12px 48px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    outline: none;
}

.btn-clear {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    font-size: 1.1rem;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s ease;
    z-index: 2;
}

.btn-clear:hover {
    color: #dc3545;
}

.students-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.student-item {
    background: white;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    transition: all 0.3s ease;
}

.student-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.student-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-grow: 1;
    min-width: 0;
}

.student-avatar-small {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
}

.student-avatar-small-placeholder {
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

.student-actions-small {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.student-actions-small .btn {
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

.min-width-0 {
    min-width: 0;
}

@media (max-width: 576px) {
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 768px) {
    .detail-page {
        padding-bottom: 20px;
        max-width: 900px;
        margin: 0 auto;
    }
}
</style>

<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const studentItems = document.querySelectorAll('.student-item');
    const noResults = document.getElementById('noResults');
    const emptyState = document.getElementById('emptyState');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }
            performSearch();
        });
        
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            performSearch();
            searchInput.focus();
        });
    }
    
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        studentItems.forEach(item => {
            const name = item.getAttribute('data-name');
            const email = item.getAttribute('data-email');
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        if (searchTerm && visibleCount === 0) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
        
        if (emptyState && searchTerm) {
            emptyState.style.display = 'none';
        } else if (emptyState && !searchTerm) {
            emptyState.style.display = studentItems.length === 0 ? 'block' : 'none';
        }
    }
});
</script>
@endsection
