@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-graduation-cap me-2"></i>Dashboard Siswa</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('student.attendance') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-camera me-1"></i>Absen Sekarang
            </a>
            <a href="{{ route('student.schedule') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-calendar-alt me-1"></i>Lihat Jadwal
            </a>
        </div>
    </div>
</div>

<!-- Student Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name ?? 'Student') }}" 
                             class="rounded-circle" width="80" height="80" alt="Student Photo">
                    </div>
                    <div class="col">
                        <h4 class="mb-1">{{ $student->name ?? 'Nama Siswa' }}</h4>
                        <p class="text-muted mb-1">NISN: {{ $student->nisn ?? '0000000000' }}</p>
                        <p class="text-muted mb-1">Kelas: {{ $student->classRoom->name ?? 'Belum Ada Kelas' }}</p>
                        <span class="badge bg-{{ isset($attendancePercentage) && $attendancePercentage >= 80 ? 'success' : ($attendancePercentage >= 60 ? 'warning' : 'danger') }}">
                            Kehadiran: {{ $attendancePercentage ?? 0 }}%
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="text-center">
                            <h3 class="mb-0">{{ $attendancePercentage ?? 0 }}%</h3>
                            <small class="text-muted">Tingkat Kehadiran</small>
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

<!-- Today's Schedule and Recent Attendance -->
<div class="row mb-4">
    <!-- Today's Schedule -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-day me-2"></i>Jadwal Pelajaran Hari Ini
                </h5>
            </div>
            <div class="card-body">
                @if(isset($todaySchedules) && $todaySchedules->count() > 0)
                    <div class="row">
                        @foreach($todaySchedules as $schedule)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $schedule->subject->name }}</h6>
                                    @php
                                        $now = now();
                                        $startTime = \Carbon\Carbon::parse($schedule->start_time);
                                        $endTime = \Carbon\Carbon::parse($schedule->end_time);
                                        $canAttend = $now->between($startTime->subMinutes(15), $endTime);
                                        $isActive = $now->between($startTime, $endTime);
                                    @endphp
                                    
                                    @if($isActive)
                                        <span class="badge bg-success">Sedang Berlangsung</span>
                                    @elseif($canAttend)
                                        <span class="badge bg-warning">Dapat Absen</span>
                                    @else
                                        <span class="badge bg-primary">{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}</span>
                                    @endif
                                </div>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user me-1"></i>{{ $schedule->teacher->name }}
                                </p>
                                <p class="text-muted mb-3">
                                    <i class="fas fa-door-open me-1"></i>{{ $schedule->room ?? 'Ruang TBA' }}
                                </p>
                                
                                @if($canAttend)
                                    @php
                                        // Check if already attended today
                                        $alreadyAttended = \App\Models\Attendance::where('student_id', $student->id)
                                                                                ->where('schedule_id', $schedule->id)
                                                                                ->where('date', now()->toDateString())
                                                                                ->exists();
                                    @endphp
                                    
                                    @if($alreadyAttended)
                                        <button class="btn btn-success btn-sm w-100" disabled>
                                            <i class="fas fa-check me-1"></i>Sudah Absen
                                        </button>
                                    @else
                                        <a href="{{ route('student.attendance') }}#schedule-{{ $schedule->id }}" 
                                           class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-camera me-1"></i>Absen Sekarang
                                        </a>
                                    @endif
                                @else
                                    <button class="btn btn-secondary btn-sm w-100" disabled>
                                        @if($now->lt($startTime))
                                            <i class="fas fa-clock me-1"></i>Belum Waktunya
                                        @else
                                            <i class="fas fa-times me-1"></i>Waktu Habis
                                        @endif
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <h5>Tidak Ada Jadwal Hari Ini</h5>
                        <p>Enjoy your free day!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Attendance Chart -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Statistik Kehadiran
                </h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance History -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Riwayat Absensi Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recentAttendances) && $recentAttendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d M Y') }}</td>
                                    <td>{{ $attendance->schedule->subject->name }}</td>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $attendance->getStatusColor() }}">
                                            {{ $attendance->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <h5>Belum Ada Riwayat Absensi</h5>
                        <p>Mulai absensi untuk melihat riwayat kehadiran Anda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Attendance Pie Chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
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
