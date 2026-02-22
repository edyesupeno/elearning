@extends('layouts.app')

@section('title', 'Kelola Siswa')

@section('content')
<div class="student-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h2 class="mb-1 fw-bold">Kelola Siswa</h2>
                <p class="text-muted mb-0 small">Manajemen data siswa dan murid</p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-add shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span class="ms-2">Tambah</span>
            </a>
        </div>
        
        <!-- Search Bar -->
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari nama atau email siswa...">
            <button class="btn-clear" id="clearSearch" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
    </div>

    <!-- Student List -->
    <div class="student-list" id="studentList">
        @forelse($students as $student)
            <div class="student-card" data-name="{{ strtolower($student->name) }}" data-email="{{ strtolower($student->email) }}" data-class="{{ $student->classRoom ? strtolower($student->classRoom->name) : '' }}">
                <div class="student-card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($student->avatar)
                            <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->name }}" class="student-avatar">
                        @else
                            <div class="student-avatar-placeholder">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1 ms-3 min-width-0">
                            <h6 class="mb-1 fw-semibold text-truncate">{{ $student->name }}</h6>
                            <p class="text-muted small mb-0 text-truncate">
                                <i class="bi bi-envelope me-1"></i>{{ $student->email }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="student-stats mb-3">
                        <div class="stat-item">
                            <i class="bi bi-door-open text-info"></i>
                            <span>{{ $student->classRoom ? $student->classRoom->name : 'Tanpa Kelas' }}</span>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-book text-primary"></i>
                            <span>{{ $student->enrollments_count }} Mapel</span>
                        </div>
                    </div>
                    
                    <div class="student-actions">
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-pencil-square me-1"></i>Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="confirmDelete({{ $student->id }})">
                            <i class="bi bi-trash3 me-1"></i>Hapus
                        </button>
                    </div>
                </div>
                <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @empty
            <div class="empty-state" id="emptyState">
                <div class="empty-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h5 class="mt-3 mb-2">Belum ada data siswa</h5>
                <p class="text-muted mb-3">Klik tombol "Tambah" untuk menambahkan siswa baru</p>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Siswa Pertama
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- No Results State -->
    <div class="empty-state" id="noResults" style="display: none;">
        <div class="empty-icon bg-secondary">
            <i class="bi bi-search"></i>
        </div>
        <h5 class="mt-3 mb-2">Tidak ada hasil</h5>
        <p class="text-muted mb-0">Tidak ditemukan siswa dengan kata kunci tersebut</p>
    </div>
</div>

<style>
.student-page {
    padding-bottom: 100px;
}

.page-header h2 {
    color: #1a1a1a;
}

.btn-add {
    padding: 8px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
}

.btn-add i {
    font-size: 1rem;
}

.search-wrapper {
    position: relative;
    margin-top: 16px;
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1.1rem;
    pointer-events: none;
    z-index: 2;
}

.search-input {
    padding: 14px 48px 14px 48px;
    border-radius: 16px;
    border: 2px solid #e9ecef;
    font-size: 0.95rem;
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
    font-size: 1.2rem;
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

.student-list {
    display: grid;
    gap: 16px;
}

.student-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.student-card:active {
    transform: scale(0.98);
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
}

.student-card-body {
    padding: 20px;
}

.student-avatar {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    object-fit: cover;
    flex-shrink: 0;
}

.student-avatar-placeholder {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    flex-shrink: 0;
}

.student-stats {
    display: flex;
    gap: 16px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 12px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6c757d;
}

.stat-item i {
    font-size: 1rem;
}

.student-actions {
    display: flex;
    gap: 8px;
}

.student-actions .btn {
    border-radius: 12px;
    font-weight: 500;
    padding: 10px 16px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.empty-icon.bg-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.empty-state h5 {
    color: #1a1a1a;
    font-weight: 600;
}

.empty-state p {
    font-size: 0.95rem;
}

.min-width-0 {
    min-width: 0;
}

@media (max-width: 576px) {
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .btn-add {
        padding: 8px 16px;
        font-size: 0.85rem;
    }
    
    .student-stats {
        flex-direction: column;
        gap: 8px;
    }
}

@media (min-width: 768px) {
    .student-page {
        padding-bottom: 20px;
    }
    
    .student-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 992px) {
    .student-list {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus siswa ini?\nData enrollment siswa akan ikut terhapus.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const studentCards = document.querySelectorAll('.student-card');
    const noResults = document.getElementById('noResults');
    const emptyState = document.getElementById('emptyState');
    
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
    
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        studentCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const email = card.getAttribute('data-email');
            const className = card.getAttribute('data-class');
            
            if (name.includes(searchTerm) || email.includes(searchTerm) || className.includes(searchTerm)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
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
            emptyState.style.display = studentCards.length === 0 ? 'block' : 'none';
        }
    }
});
</script>
@endsection
