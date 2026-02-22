@extends('layouts.app')

@section('title', 'Kelola Kelas')

@section('content')
<div class="class-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h2 class="mb-1 fw-bold">Kelola Kelas</h2>
                <p class="text-muted mb-0 small">Manajemen kelas dan rombongan belajar</p>
            </div>
            <a href="{{ route('admin.classes.create') }}" class="btn btn-primary btn-add shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span class="ms-2">Tambah</span>
            </a>
        </div>
        
        <!-- Search Bar -->
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari nama kelas...">
            <button class="btn-clear" id="clearSearch" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
    </div>

    <!-- Class List -->
    <div class="class-list" id="classList">
        @forelse($classes as $class)
            <div class="class-card" data-name="{{ strtolower($class->name) }}" data-grade="{{ strtolower($class->grade) }}">
                <div class="class-card-header">
                    <div class="class-icon">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="mb-1 fw-semibold">{{ $class->name }}</h6>
                        @if($class->grade || $class->major)
                            <p class="text-muted small mb-0">
                                @if($class->grade)
                                    <span class="badge bg-primary me-1">Kelas {{ $class->grade }}</span>
                                @endif
                                @if($class->major)
                                    <span class="badge bg-info">{{ $class->major }}</span>
                                @endif
                            </p>
                        @endif
                    </div>
                    <span class="badge {{ $class->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                        {{ $class->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <div class="class-card-body">
                    @if($class->description)
                        <p class="class-description">{{ Str::limit($class->description, 80) }}</p>
                    @endif
                    
                    <div class="class-stats mb-3">
                        <div class="stat-item">
                            <i class="bi bi-people text-primary"></i>
                            <span>{{ $class->students_count }} Siswa</span>
                        </div>
                    </div>
                    
                    <div class="class-actions">
                        <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                        <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-outline-warning flex-fill">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="confirmDelete({{ $class->id }})">
                            <i class="bi bi-trash3 me-1"></i>Hapus
                        </button>
                    </div>
                </div>
                <form id="delete-form-{{ $class->id }}" action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @empty
            <div class="empty-state" id="emptyState">
                <div class="empty-icon">
                    <i class="bi bi-door-open"></i>
                </div>
                <h5 class="mt-3 mb-2">Belum ada kelas</h5>
                <p class="text-muted mb-3">Klik tombol "Tambah" untuk membuat kelas baru</p>
                <a href="{{ route('admin.classes.create') }}" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Kelas Pertama
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
        <p class="text-muted mb-0">Tidak ditemukan kelas dengan kata kunci tersebut</p>
    </div>
</div>

<style>
.class-page {
    padding-bottom: 100px;
}

.page-header h2 {
    color: #1a1a1a;
}

.btn.rounded-pill {
    padding: 10px 24px;
    font-weight: 500;
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

.class-list {
    display: grid;
    gap: 16px;
}

.class-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.class-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.class-card-header {
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    gap: 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.class-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.75rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.class-card-body {
    padding: 20px;
}

.class-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 16px;
}

.class-stats {
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

.class-actions {
    display: flex;
    gap: 8px;
}

.class-actions .btn {
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
    
    .class-actions {
        flex-direction: column;
    }
}

@media (min-width: 768px) {
    .class-page {
        padding-bottom: 20px;
    }
    
    .class-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 992px) {
    .class-list {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus kelas ini?\nSiswa di kelas ini akan dipindahkan ke "Tanpa Kelas".')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const classCards = document.querySelectorAll('.class-card');
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
        
        classCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const grade = card.getAttribute('data-grade');
            
            if (name.includes(searchTerm) || grade.includes(searchTerm)) {
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
            emptyState.style.display = classCards.length === 0 ? 'block' : 'none';
        }
    }
});
</script>
@endsection
