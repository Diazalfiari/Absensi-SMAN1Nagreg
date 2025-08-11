@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user me-2"></i>Detail Siswa</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Student Profile Card -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}" 
                     class="rounded-circle mb-3" width="120" height="120" alt="Student Photo">
                <h5 class="card-title">{{ $student->name }}</h5>
                <p class="text-muted">{{ $student->nisn }}</p>
                <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'danger' }} fs-6">
                    {{ ucfirst($student->status) }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Nama Lengkap:</td>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">NISN:</td>
                                <td>{{ $student->nisn }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">NIS:</td>
                                <td>{{ $student->nis }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>{{ $student->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jenis Kelamin:</td>
                                <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Tempat, Tanggal Lahir:</td>
                                <td>{{ $student->birth_place }}, {{ \Carbon\Carbon::parse($student->birth_date)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kelas:</td>
                                <td>{{ $student->classRoom->name ?? 'Belum ada kelas' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal Masuk:</td>
                                <td>{{ \Carbon\Carbon::parse($student->entry_date)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">No. HP:</td>
                                <td>{{ $student->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">HP Orang Tua:</td>
                                <td>{{ $student->parent_phone ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Alamat:</td>
                                <td>{{ $student->address }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Hadir</h5>
                        <h3 class="mt-1 mb-0">{{ $attendanceStats['hadir'] }}</h3>
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
                        <h3 class="mt-1 mb-0">{{ $attendanceStats['sakit'] }}</h3>
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
                        <h3 class="mt-1 mb-0">{{ $attendanceStats['izin'] }}</h3>
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
                        <h3 class="mt-1 mb-0">{{ $attendanceStats['alpha'] }}</h3>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Percentage -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Persentase Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <h2 class="mb-0 text-{{ $attendancePercentage >= 80 ? 'success' : ($attendancePercentage >= 60 ? 'warning' : 'danger') }}">
                            {{ $attendancePercentage }}%
                        </h2>
                    </div>
                    <div class="flex-grow-1">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-{{ $attendancePercentage >= 80 ? 'success' : ($attendancePercentage >= 60 ? 'warning' : 'danger') }}" 
                                 role="progressbar" style="width: {{ $attendancePercentage }}%">
                            </div>
                        </div>
                        <small class="text-muted">
                            Dari total {{ array_sum($attendanceStats) }} hari kehadiran
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance History -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Riwayat Kehadiran Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($student->attendances) && $student->attendances->count() > 0)
                        @foreach($student->attendances->take(10) as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                            <td>{{ $attendance->schedule->subject->name ?? '-' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($attendance->schedule->start_time ?? '00:00')->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($attendance->schedule->end_time ?? '00:00')->format('H:i') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $attendance->status === 'hadir' ? 'success' : 
                                    ($attendance->status === 'sakit' ? 'warning' : 
                                    ($attendance->status === 'izin' ? 'info' : 'danger')) 
                                }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td>{{ $attendance->note ?? '-' }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada riwayat kehadiran</h6>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
