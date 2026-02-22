@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="container" style="padding-bottom: 100px;">
    <h2 class="mb-4">Pengaturan Aplikasi</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Error:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informasi Aplikasi</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nama Aplikasi</label>
                    <input type="text" name="app_name" class="form-control" value="{{ old('app_name', $settings['app_name']) }}" required>
                </div>

                <div class="mb-0">
                    <label class="form-label">Deskripsi Aplikasi</label>
                    <textarea name="app_description" class="form-control" rows="3">{{ old('app_description', $settings['app_description']) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Logo Aplikasi</h5>
            </div>
            <div class="card-body">
                @if($settings['app_logo'])
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $settings['app_logo']) }}" style="max-height: 150px;" class="img-thumbnail">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="delete_logo" value="1" id="deleteLogo">
                            <label class="form-check-label text-danger" for="deleteLogo">
                                Hapus logo yang ada
                            </label>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="form-label">Upload Logo Baru</label>
                    <input type="file" name="app_logo" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, SVG. Max 2MB</small>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Favicon</h5>
            </div>
            <div class="card-body">
                @if($settings['app_favicon'])
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $settings['app_favicon']) }}" style="max-height: 64px;" class="img-thumbnail">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="delete_favicon" value="1" id="deleteFavicon">
                            <label class="form-check-label text-danger" for="deleteFavicon">
                                Hapus favicon yang ada
                            </label>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="form-label">Upload Favicon Baru</label>
                    <input type="file" name="app_favicon" class="form-control" accept=".ico,.png">
                    <small class="text-muted">Format: ICO, PNG. Max 1MB</small>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-check-circle me-2"></i>Simpan Pengaturan
        </button>
    </form>
</div>

@endsection
