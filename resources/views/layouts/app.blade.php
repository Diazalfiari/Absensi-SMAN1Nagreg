<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Absensi SMAN 1 Nagreg') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8fafc;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(5px);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }

        .stat-card {
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
            border-left: 4px solid var(--primary-color);
        }

        .stat-card.success {
            border-left-color: var(--success-color);
        }

        .stat-card.warning {
            border-left-color: var(--warning-color);
        }

        .stat-card.danger {
            border-left-color: var(--danger-color);
        }

        .camera-preview {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .attendance-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-hadir {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-sakit {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-izin {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-alpha {
            background-color: #fecaca;
            color: #991b1b;
        }

        .table th {
            background-color: #f8fafc;
            border: none;
            font-weight: 600;
            color: var(--dark-color);
        }

        .table td {
            border-color: #e2e8f0;
            vertical-align: middle;
        }

        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-bottom: 1px solid #e2e8f0;
            z-index: 1020;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 280px;
            height: 100vh;
            overflow-y: auto;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        /* User Profile Section Styles */
        .user-profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .user-avatar-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .user-avatar {
            border: 2px solid rgba(255,255,255,0.3);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        @media (max-width: 767.98px) {
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @guest
    <!-- Guest layout - login/register pages -->
    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        @if (isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </div>
    @endguest

    @auth
    <!-- Authenticated layout with sidebar -->
    <!-- Sidebar Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar d-flex flex-column">
        <div class="p-3 flex-grow-1">
            <div class="text-center mb-4">
                <h5 class="text-white mb-0">SMAN 1 Nagreg</h5>
                <small class="text-white-50">Sistem Absensi</small>
            </div>

            <!-- User Info -->
            <div class="user-profile-section mb-4 p-3 rounded" style="background-color: rgba(255,255,255,0.1);">
                <div class="user-avatar-container">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=50&background=ffffff&color=2563eb&font-size=0.6" 
                         alt="{{ auth()->user()->name }}" 
                         width="50" 
                         height="50" 
                         class="user-avatar rounded-circle">
                </div>
                <div class="user-info text-white">
                    <div class="fw-bold mb-1">{{ auth()->user()->name }}</div>
                    <small class="text-white-50">{{ ucfirst(auth()->user()->role) }}</small>
                </div>
            </div>

            <ul class="nav flex-column">
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-pie me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.students.index') }}">
                            <i class="fas fa-users me-2"></i>
                            Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.teachers.index') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Data Guru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.classes.index') }}">
                            <i class="fas fa-school me-2"></i>
                            Data Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.subjects.index') }}">
                            <i class="fas fa-book me-2"></i>
                            Mata Pelajaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.schedules.index') }}">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Jadwal Pelajaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.attendances.index') }}">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Data Absensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>
                            Laporan
                        </a>
                    </li>
                @elseif(auth()->user()->role === 'teacher')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" 
                           href="{{ route('teacher.dashboard') }}">
                            <i class="fas fa-chart-pie me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Jadwal Mengajar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Absensi Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users me-2"></i>
                            Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-bar me-2"></i>
                            Laporan Kelas
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" 
                           href="{{ route('student.dashboard') }}">
                            <i class="fas fa-chart-pie me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.schedule') ? 'active' : '' }}" 
                           href="{{ route('student.schedule') }}">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Jadwal Pelajaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.attendance') && !request()->routeIs('student.attendance.history') ? 'active' : '' }}" 
                           href="{{ route('student.attendance') }}">
                            <i class="fas fa-camera me-2"></i>
                            Absensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.attendance.history') ? 'active' : '' }}" 
                           href="{{ route('student.attendance.history') }}">
                            <i class="fas fa-history me-2"></i>
                            Riwayat Absensi
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Logout Section -->
        <div class="p-3 border-top border-light border-opacity-25">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
            <div class="container-fluid">
                <button class="btn btn-outline-primary d-md-none me-3" type="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="navbar-brand d-md-none">
                    <strong>SMAN 1 Nagreg</strong>
                </div>
                
                <div class="d-flex align-items-center ms-auto">
                    <span class="text-muted me-3 d-none d-sm-inline">
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ now()->format('d F Y') }}
                    </span>
                    <span class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        <span id="current-time">{{ now()->format('H:i:s') }}</span>
                    </span>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid py-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (isset($header))
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    {{ $header }}
                </div>
            @endif

            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </div>
    </main>
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Real-time clock and mobile navigation -->
    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        // Update time every second
        setInterval(updateTime, 1000);
        updateTime(); // Initial call

        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
            
            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }
            
            // Close sidebar when clicking on navigation links (mobile)
            if (sidebar) {
                sidebar.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 768) {
                            closeSidebar();
                        }
                    });
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
