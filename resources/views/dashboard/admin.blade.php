@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="mb-0">Dashboard Admin</h2>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-book text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Kursus</h6>
                        <h2 class="mb-0">{{ $stats['total_courses'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-person-badge text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Guru</h6>
                        <h2 class="mb-0">{{ $stats['total_teachers'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Murid</h6>
                        <h2 class="mb-0">{{ $stats['total_students'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Menu Manajemen</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('admin.teachers.index') }}" class="text-decoration-none">
                            <div class="card border-0 bg-light h-100 hover-shadow">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-person-badge text-primary me-3" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Kelola Guru</h6>
                                        <small class="text-muted">Tambah, edit, hapus data guru</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('courses.index') }}" class="text-decoration-none">
                            <div class="card border-0 bg-light h-100 hover-shadow">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-book text-success me-3" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Kelola Kursus</h6>
                                        <small class="text-muted">Lihat semua kursus</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.settings.index') }}" class="text-decoration-none">
                            <div class="card border-0 bg-light h-100 hover-shadow">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-gear text-info me-3" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Pengaturan</h6>
                                        <small class="text-muted">Logo, favicon, info aplikasi</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection
