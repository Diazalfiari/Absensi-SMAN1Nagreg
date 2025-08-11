@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-chalkboard-teacher me-2"></i>Dashboard Guru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-clipboard-check me-1"></i>Kelola Absensi
            </button>
        </div>
    </div>
</div>

<!-- Teacher Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}" 
                             class="rounded-circle" width="80" height="80" alt="Teacher Photo">
                    </div>
                    <div class="col">
                        <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                        <p class="text-muted mb-1">{{ auth()->user()->email }}</p>
                        <p class="text-muted mb-0">Guru - SMAN 1 Nagreg</p>
                    </div>
                    <div class="col-auto">
                        <div class="text-center">
                            <h3 class="mb-0">{{ isset($todaySchedules) ? $todaySchedules->count() : 0 }}</h3>
                            <small class="text-muted">Kelas Hari Ini</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Hadir</h5>
                        <h3 class="mt-1 mb-0">{{ isset($attendanceStats['hadir']) ? $attendanceStats['hadir'] : 0 }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Sakit</h5>
                        <h3 class="mt-1 mb-0">{{ isset($attendanceStats['sakit']) ? $attendanceStats['sakit'] : 0 }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-thermometer-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Izin</h5>
                        <h3 class="mt-1 mb-0">{{ isset($attendanceStats['izin']) ? $attendanceStats['izin'] : 0 }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-hand-paper fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Alpha</h5>
                        <h3 class="mt-1 mb-0">{{ isset($attendanceStats['alpha']) ? $attendanceStats['alpha'] : 0 }}</h3>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-day me-2"></i>Jadwal Mengajar Hari Ini
                </h5>
            </div>
            <div class="card-body">
                @if(isset($todaySchedules) && $todaySchedules->count() > 0)
                    <div class="row">
                        @foreach($todaySchedules as $schedule)
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $schedule->subject->name }}</h6>
                                        <span class="badge bg-primary">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-users me-1"></i>{{ $schedule->classRoom->name }}
                                    </p>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-door-open me-1"></i>{{ $schedule->room ?? 'Ruang TBA' }}
                                    </p>
                                    <div class="d-grid">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-clipboard-check me-1"></i>Kelola Absensi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <h5>Tidak Ada Jadwal Mengajar Hari Ini</h5>
                        <p>Anda tidak memiliki kelas hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Stats Chart -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Statistik Bulan Ini
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyStatsChart" height="250"></canvas>
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
                            <i class="fas fa-clipboard-check me-2"></i>Absen Kelas
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-calendar-alt me-2"></i>Lihat Jadwal
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-chart-bar me-2"></i>Laporan Kelas
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-users me-2"></i>Data Siswa
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
    // Monthly Statistics Chart
    const ctx = document.getElementById('monthlyStatsChart').getContext('2d');
    new Chart(ctx, {
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
</script>
@endpush
