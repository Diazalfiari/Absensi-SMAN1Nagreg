@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-chart-pie me-2"></i>Dashboard Admin</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download me-1"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total Siswa</h5>
                        <h3 class="mt-1 mb-0">{{ $totalStudents ?? 0 }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total Kelas</h5>
                        <h3 class="mt-1 mb-0">{{ $totalClasses ?? 0 }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-school fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Jadwal Hari Ini</h5>
                        <h3 class="mt-1 mb-0">{{ $totalSchedulesToday ?? 0 }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Kehadiran Hari Ini</h5>
                        <h3 class="mt-1 mb-0">{{ isset($attendanceStats) ? array_sum($attendanceStats) : 0 }}</h3>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-clipboard-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Attendance Status Chart -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Status Absensi Hari Ini
                </h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceStatusChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Weekly Attendance Trend -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>Tren Kehadiran 7 Hari Terakhir
                </h5>
            </div>
            <div class="card-body">
                <canvas id="weeklyTrendChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Class Statistics -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistik Kehadiran Per Kelas (Bulan Ini)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="classStatsChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body">
                <div class="activity-list" style="max-height: 300px; overflow-y: auto;">
                    @if(isset($recentActivities) && $recentActivities->count() > 0)
                        @foreach($recentActivities as $activity)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($activity->student->user->name) }}" 
                                     class="rounded-circle" width="40" height="40" alt="Avatar">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $activity->student->user->name }}</h6>
                                <small class="text-muted">
                                    {{ $activity->schedule->subject->name }} - {{ $activity->schedule->classRoom->name }}
                                </small>
                                <div>
                                    <span class="attendance-status status-{{ $activity->status }}">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                    <small class="text-muted ms-2">
                                        {{ $activity->check_in ? $activity->check_in->format('H:i') : '-' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Belum ada aktivitas hari ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="#" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-user-plus me-2"></i>Tambah Siswa
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-calendar-plus me-2"></i>Buat Jadwal
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-file-export me-2"></i>Export Laporan
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-cog me-2"></i>Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Attendance Status Pie Chart
    const attendanceCtx = document.getElementById('attendanceStatusChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
            datasets: [{
                data: [
                    {{ isset($attendanceStats['hadir']) ? $attendanceStats['hadir'] : 0 }},
                    {{ isset($attendanceStats['sakit']) ? $attendanceStats['sakit'] : 0 }},
                    {{ isset($attendanceStats['izin']) ? $attendanceStats['izin'] : 0 }},
                    {{ isset($attendanceStats['alpha']) ? $attendanceStats['alpha'] : 0 }}
                ],
                backgroundColor: [
                    '#059669',
                    '#d97706', 
                    '#2563eb',
                    '#dc2626'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Weekly Trend Line Chart
    const weeklyCtx = document.getElementById('weeklyTrendChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'line',
        data: {
            labels: [
                @if(isset($weeklyData))
                    @foreach($weeklyData as $day)
                        '{{ $day['date'] }}',
                    @endforeach
                @endif
            ],
            datasets: [{
                label: 'Kehadiran',
                data: [
                    @if(isset($weeklyData))
                        @foreach($weeklyData as $day)
                            {{ $day['hadir'] }},
                        @endforeach
                    @endif
                ],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Class Statistics Bar Chart
    const classCtx = document.getElementById('classStatsChart').getContext('2d');
    new Chart(classCtx, {
        type: 'bar',
        data: {
            labels: [
                @if(isset($classStats))
                    @foreach($classStats as $class)
                        '{{ $class['name'] }}',
                    @endforeach
                @endif
            ],
            datasets: [{
                label: 'Tingkat Kehadiran (%)',
                data: [
                    @if(isset($classStats))
                        @foreach($classStats as $class)
                            {{ $class['attendance_rate'] }},
                        @endforeach
                    @endif
                ],
                backgroundColor: '#2563eb',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
