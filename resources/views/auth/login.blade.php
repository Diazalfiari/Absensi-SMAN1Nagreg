<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi SMAN 1 Nagreg</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .school-header {
            background: linear-gradient(45deg, #2c3e50, #3498db);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .school-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #2c3e50;
        }
        
        .form-container {
            padding: 2rem;
        }
        
        .form-floating > label {
            color: #666;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(45deg, #3498db, #2c3e50);
            border: none;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .demo-accounts {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .demo-account {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.5rem;
            margin: 0.5rem 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .demo-account:hover {
            border-color: #3498db;
            transform: translateY(-1px);
        }
        
        .demo-account small {
            color: #666;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-container">
                    <!-- School Header -->
                    <div class="school-header">
                        <div class="school-logo">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="mb-2">SMAN 1 NAGREG</h3>
                        <p class="mb-0">Sistem Absensi Digital</p>
                        <small class="opacity-75">Smart Attendance System</small>
                    </div>

                    <!-- Login Form -->
                    <div class="form-container">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Input -->
                            <div class="form-floating mb-3">
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="name@example.com" 
                                       required 
                                       autofocus>
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="form-floating mb-3">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Password" 
                                       required>
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label" for="remember_me">
                                    Remember me
                                </label>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>

                            <!-- Forgot Password -->
                            @if (Route::has('password.request'))
                                <div class="text-center">
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                                        <i class="fas fa-key me-1"></i>Forgot your password?
                                    </a>
                                </div>
                            @endif
                        </form>

                        <!-- Demo Accounts -->
                        <div class="demo-accounts">
                            <h6 class="mb-3">
                                <i class="fas fa-users me-2"></i>Demo Accounts
                            </h6>
                            
                            <div class="demo-account" onclick="fillLogin('admin@smansan.sch.id', 'password')">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-user-shield text-danger"></i>
                                    </div>
                                    <div>
                                        <strong>Administrator</strong>
                                        <br><small>admin@smansan.sch.id</small>
                                    </div>
                                </div>
                            </div>

                            <div class="demo-account" onclick="fillLogin('teacher@smansan.sch.id', 'password')">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-chalkboard-teacher text-warning"></i>
                                    </div>
                                    <div>
                                        <strong>Teacher</strong>
                                        <br><small>teacher@smansan.sch.id</small>
                                    </div>
                                </div>
                            </div>

                            <div class="demo-account" onclick="fillLogin('student@smansan.sch.id', 'password')">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-user-graduate text-success"></i>
                                    </div>
                                    <div>
                                        <strong>Student</strong>
                                        <br><small>student@smansan.sch.id</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
