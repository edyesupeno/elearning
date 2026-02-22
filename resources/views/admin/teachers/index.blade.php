@extends('layouts.app')

@section('title', 'Kelola Guru')

@section('content')
<div class="teacher-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h2 class="mb-1 fw-bold">Kelola Guru</h2>
                <p class="text-muted mb-0 small">Manajemen data guru dan pengajar</p>
            </div>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah
            </a>
        </div>
        
        <!-- Search Bar -->
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari nama atau email guru...">
            <button class="btn-clear" id="clearSearch" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
    </div>

    <!-- Teacher List -->
    <div class="teacher-list" id="teacherList">
        @forelse($teachers as $teacher)
            <div class="teacher-card" data-name="{{ strtolower($teacher->name) }}" data-email="{{ strtolower($teacher->email) }}">
                <div class="teacher-card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($teacher->avatar)
                            <img src="{{ asset('storage/' . $teacher->avatar) }}" alt="{{ $teacher->name }}" class="teacher-avatar">
                        @else
                            <div class="teacher-avatar-placeholder">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1 ms-3 min-width-0">
                            <h6 class="mb-1 fw-semibold text-truncate">{{ $teacher->name }}</h6>
                            <p class="text-muted small mb-0 text-truncate">
                                <i class="bi bi-envelope me-1"></i>{{ $teacher->email }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="teacher-stats mb-3">
                        <div class="stat-item">
                            <i class="bi bi-book text-primary"></i>
                            <span>{{ $teacher->courses_count }} Mapel</span>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-calendar-check text-success"></i>
                            <span>{{ $teacher->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="teacher-actions">
                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-pencil-square me-1"></i>Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="confirmDelete({{ $teacher->id }})">
                            <i class="bi bi-trash3 me-1"></i>Hapus
                        </button>
                    </div>
                </div>
                <form id="delete-form-{{ $teacher->id }}" action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @empty
            <div class="empty-state" id="emptyState">
                <div class="empty-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h5 class="mt-3 mb-2">Belum ada data guru</h5>
                <p class="text-muted mb-3">Klik tombol "Tambah" untuk menambahkan guru baru</p>
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Guru Pertama
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
        <p class="text-muted mb-0">Tidak ditemukan guru dengan kata kunci tersebut</p>
    </div>
</div>

<style>
.teacher-page {
    padding-bottom: 100px;
}

.page-header h2 {
    color: #1a1a1a;
}

.btn.rounded-pill {
    padding: 10px 24px;
    font-weight: 500;
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

.teacher-list {
    display: grid;
    gap: 16px;
}

.teacher-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.teacher-card:active {
    transform: scale(0.98);
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
}

.teacher-card-body {
    padding: 20px;
}

.teacher-avatar {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    object-fit: cover;
    flex-shrink: 0;
}

.teacher-avatar-placeholder {
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

.teacher-stats {
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

.teacher-actions {
    display: flex;
    gap: 8px;
}

.teacher-actions .btn {
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
    
    .btn.rounded-pill {
        padding: 8px 20px;
        font-size: 0.9rem;
    }
    
    .teacher-stats {
        flex-direction: column;
        gap: 8px;
    }
}

@media (min-width: 768px) {
    .teacher-page {
        padding-bottom: 20px;
    }
    
    .teacher-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 992px) {
    .teacher-list {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus guru ini?\nSemua kursus yang dibuat akan ikut terhapus.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const teacherCards = document.querySelectorAll('.teacher-card');
    const noResults = document.getElementById('noResults');
    const emptyState = document.getElementById('emptyState');
    
    // Show/hide clear button
    searchInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            clearBtn.style.display = 'flex';
        } else {
            clearBtn.style.display = 'none';
        }
        performSearch();
    });
    
    // Clear search
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        clearBtn.style.display = 'none';
        performSearch();
        searchInput.focus();
    });
    
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        teacherCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const email = card.getAttribute('data-email');
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (searchTerm && visibleCount === 0) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
        
        // Hide empty state when searching
        if (emptyState && searchTerm) {
            emptyState.style.display = 'none';
        } else if (emptyState && !searchTerm) {
            emptyState.style.display = teacherCards.length === 0 ? 'block' : 'none';
        }
    }
});
</script>
@endsection
