<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', App\Models\Setting::get('app_name', 'E-Learning'))</title>
    @if(App\Models\Setting::get('app_favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . App\Models\Setting::get('app_favicon')) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --text-primary: #1a1a1a;
            --text-secondary: #8e8e93;
            --bg-light: #f8f9fa;
        }
        
        body {
            padding-bottom: 85px;
            background: var(--bg-light);
        }
        
        /* App Header */
        .app-header {
            background: white;
            padding: 16px 0;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            margin-bottom: 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .app-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .app-logo-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .app-logo-wrapper img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }
        
        .app-logo-placeholder {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .app-name {
            font-size: 1.25rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            padding: 12px 0 16px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .bottom-nav::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
        }
        
        .bottom-nav .nav {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 8px;
        }
        
        .bottom-nav .nav-item {
            flex: 1;
            text-align: center;
        }
        
        .bottom-nav .nav-link {
            color: var(--text-secondary);
            padding: 8px 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.7rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border-radius: 16px;
        }
        
        .bottom-nav .nav-link i {
            font-size: 1.5rem;
            margin-bottom: 4px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .bottom-nav .nav-link.active {
            color: var(--primary-color);
        }
        
        .bottom-nav .nav-link.active i {
            transform: translateY(-2px);
        }
        
        .bottom-nav .nav-link.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        }
        
        .bottom-nav .nav-link:active {
            transform: scale(0.92);
        }
        
        .bottom-nav .nav-link:hover:not(.active) {
            background: rgba(102, 126, 234, 0.05);
        }
        
        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }
            .bottom-nav {
                display: none;
            }
            .desktop-nav {
                display: block !important;
            }
            .app-header {
                display: none;
            }
        }
        
        .desktop-nav {
            display: none;
            background: var(--primary-gradient);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }
        
        .app-logo {
            max-height: 40px;
            width: auto;
        }
        
        .alert {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Desktop Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark desktop-nav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                @if(App\Models\Setting::get('app_logo'))
                    <img src="{{ asset('storage/' . App\Models\Setting::get('app_logo')) }}" alt="Logo" class="app-logo me-2">
                @endif
                {{ App\Models\Setting::get('app_name', 'E-Learning') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('courses.index') }}">Mapel</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            @if(auth()->user()->isAdmin())
                                <li><h6 class="dropdown-header">Menu Admin</h6></li>
                                <li><a class="dropdown-item" href="{{ route('admin.teachers.index') }}">Kelola Guru</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.classes.index') }}">Kelola Kelas</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.students.index') }}">Kelola Siswa</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile App Header -->
    <div class="app-header d-md-none">
        <div class="container">
            <div class="app-brand">
                <div class="app-logo-wrapper">
                    @if(App\Models\Setting::get('app_logo'))
                        <img src="{{ asset('storage/' . App\Models\Setting::get('app_logo')) }}" alt="Logo">
                    @else
                        <span class="app-logo-placeholder">{{ strtoupper(substr(App\Models\Setting::get('app_name', 'E'), 0, 1)) }}</span>
                    @endif
                </div>
                <h1 class="app-name">{{ App\Models\Setting::get('app_name', 'E-Learning') }}</h1>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi {{ request()->routeIs('dashboard') ? 'bi-house-door-fill' : 'bi-house-door' }}"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                    <i class="bi {{ request()->routeIs('courses.*') ? 'bi-book-fill' : 'bi-book' }}"></i>
                    <span>Mapel</span>
                </a>
            </li>
            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
                        <i class="bi {{ request()->routeIs('admin.teachers.*') ? 'bi-person-badge-fill' : 'bi-person-badge' }}"></i>
                        <span>Guru</span>
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Setting</span>
                </a>
            </li>
        </ul>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
