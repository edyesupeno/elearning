@extends('layouts.app')

@section('title', 'Daftar Mapel')

@section('content')
<div class="mapel-page">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h2 class="mb-1 fw-bold">Daftar Mapel</h2>
                <p class="text-muted mb-0 small">Mata pelajaran yang tersedia</p>
            </div>
            @if(auth()->user()->isTeacher())
                <a href="{{ route('courses.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>Tambah
                </a>
            @endif
        </div>
        
        <!-- Search Bar -->
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari mata pelajaran...">
            <button class="btn-clear" id="clearSearch" style="display: none;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
    </div>

    <!-- Mapel List -->
    <div class="mapel-list" id="mapelList">
        @forelse($courses as $course)
            <div class="mapel-card" data-title="{{ strtolower($course->title) }}" data-teacher="{{ strtolower($course->teacher->name) }}">
                <div class="mapel-card-header">
                    <div class="mapel-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="mb-1 fw-semibold text-truncate">{{ $course->title }}</h6>
                        <p class="text-muted small mb-0 text-truncate">
                            <i class="bi bi-person me-1"></i>{{ $course->teacher->name }}
                        </p>
                        @if($course->classes->count() > 0)
                            <div class="mt-2">
                                @foreach($course->classes as $class)
                                    <span class="badge bg-info me-1 mb-1" style="font-size: 0.7rem; font-weight: 500;">
                                        {{ $class->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mapel-card-body">
                    <p class="mapel-description">{{ Str::limit($course->description, 100) }}</p>
                    
                    <div class="mapel-stats mb-3">
                        <div class="stat-item">
                            <i class="bi bi-journal-text text-primary"></i>
                            <span>{{ $course->lessons_count }} Materi</span>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-people text-success"></i>
                            <span>{{ $course->enrollments_count }} Siswa</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm w-100 rounded-pill">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state" id="emptyState">
                <div class="empty-icon">
                    <i class="bi bi-book"></i>
                </div>
                <h5 class="mt-3 mb-2">Belum ada mata pelajaran</h5>
                <p class="text-muted mb-3">
                    @if(auth()->user()->isTeacher())
                        Klik tombol "Tambah" untuk membuat mata pelajaran baru
                    @else
                        Belum ada mata pelajaran tersedia saat ini
                    @endif
                </p>
                @if(auth()->user()->isTeacher())
                    <a href="{{ route('courses.create') }}" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Mapel Pertama
                    </a>
                @endif
            </div>
        @endforelse
    </div>
    
    <!-- No Results State -->
    <div class="empty-state" id="noResults" style="display: none;">
        <div class="empty-icon bg-secondary">
            <i class="bi bi-search"></i>
        </div>
        <h5 class="mt-3 mb-2">Tidak ada hasil</h5>
        <p class="text-muted mb-0">Tidak ditemukan mata pelajaran dengan kata kunci tersebut</p>
    </div>
</div>

<style>
.mapel-page {
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

.mapel-list {
    display: grid;
    gap: 16px;
}

.mapel-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.mapel-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.mapel-card-header {
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    gap: 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.mapel-icon {
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

.mapel-card-body {
    padding: 20px;
}

.mapel-description {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 16px;
    min-height: 48px;
}

.mapel-stats {
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
    
    .mapel-stats {
        flex-direction: column;
        gap: 8px;
    }
}

@media (min-width: 768px) {
    .mapel-page {
        padding-bottom: 20px;
    }
    
    .mapel-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 992px) {
    .mapel-list {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const mapelCards = document.querySelectorAll('.mapel-card');
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
        
        mapelCards.forEach(card => {
            const title = card.getAttribute('data-title');
            const teacher = card.getAttribute('data-teacher');
            
            if (title.includes(searchTerm) || teacher.includes(searchTerm)) {
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
            emptyState.style.display = mapelCards.length === 0 ? 'block' : 'none';
        }
    }
});
</script>
@endsection
