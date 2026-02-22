<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $settings['app_name'] ?? 'E-Learning' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @if(isset($settings['app_favicon']) && $settings['app_favicon'])
        <link rel="icon" href="{{ asset('storage/' . $settings['app_favicon']) }}">
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .app-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
            border-radius: 20px;
            object-fit: cover;
            background: white;
            padding: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .app-logo-placeholder {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
            border-radius: 20px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #667eea;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .app-name {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .app-description {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .login-body {
            padding: 40px 32px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 32px;
        }

        .welcome-text h4 {
            color: #1a1a1a;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            height: 52px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 6px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .btn-login {
            width: 100%;
            height: 52px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 576px) {
            .login-header {
                padding: 32px 24px;
            }

            .login-body {
                padding: 32px 24px;
            }

            .app-name {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header with Logo -->
            <div class="login-header">
                @if(isset($settings['app_logo']) && $settings['app_logo'])
                    <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo" class="app-logo">
                @else
                    <div class="app-logo-placeholder">
                        <i class="bi bi-book-half"></i>
                    </div>
                @endif
                <h1 class="app-name">{{ $settings['app_name'] ?? 'E-Learning' }}</h1>
                @if(isset($settings['app_description']) && $settings['app_description'])
                    <p class="app-description">{{ $settings['app_description'] }}</p>
                @endif
            </div>

            <!-- Login Form -->
            <div class="login-body">
                <div class="welcome-text">
                    <h4>Selamat Datang!</h4>
                    <p>Masuk untuk melanjutkan ke dashboard</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               required 
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="form-control" 
                                   placeholder="Masukkan password"
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
