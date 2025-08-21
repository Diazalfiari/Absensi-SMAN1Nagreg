@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-user me-2"></i>Detail Siswa
                    </h1>
                    <p class="text-muted">Informasi lengkap siswa dan riwayat kehadiran</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.students.index') }}">Data Siswa</a></li>
                        <li class="breadcrumb-item active">{{ $student->user->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Student Info Card -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" 
                                 class="rounded-circle mb-3" width="120" height="120"
                                 alt="{{ $student->user->name }}">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 120px; height: 120px; font-size: 48px;">
                                {{ strtoupper(substr($student->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="mb-1">{{ $student->user->name }}</h4>
                    <p class="text-muted mb-2">{{ $student->nis }}</p>
                    <span class="badge bg-info mb-3">{{ $student->classRoom->name }}</span>
                    
                    <div class="row text-center mt-4">
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="mb-0 text-primary">{{ $attendanceStats['hadir'] }}</h5>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="mb-0 text-warning">{{ $attendanceStats['sakit'] + $attendanceStats['izin'] }}</h5>
                                <small class="text-muted">S/I</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0 text-danger">{{ $attendanceStats['alpha'] }}</h5>
                            <small class="text-muted">Alpha</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Kontak</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Email</label>
                        <p class="mb-0">{{ $student->user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nomor Telepon</label>
                        <p class="mb-0">{{ $student->phone ?? 'Tidak tersedia' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Alamat</label>
                        <p class="mb-0">{{ $student->address ?? 'Tidak tersedia' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small">Jenis Kelamin</label>
                        <p class="mb-0">
                            <span class="badge {{ $student->gender == 'L' ? 'bg-primary' : 'bg-pink' }}">
                                {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Details -->
        <div class="col-md-8">
            <!-- Attendance Statistics -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Kehadiran</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Tingkat Kehadiran</span>
                                <strong class="text-primary">{{ $attendancePercentage }}%</strong>
                            </div>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar {{ $attendancePercentage >= 80 ? 'bg-success' : ($attendancePercentage >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                     style="width: {{ $attendancePercentage }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row text-center">
                                <div class="col-3">
                                    <h6 class="text-success">{{ $attendanceStats['hadir'] }}</h6>
                                    <small class="text-muted">Hadir</small>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-info">{{ $attendanceStats['sakit'] }}</h6>
                                    <small class="text-muted">Sakit</small>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-warning">{{ $attendanceStats['izin'] }}</h6>
                                    <small class="text-muted">Izin</small>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-danger">{{ $attendanceStats['alpha'] }}</h6>
                                    <small class="text-muted">Alpha</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Kehadiran</h6>
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </div>
                <div class="card-body p-0">
                    @if($attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jam</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td>
                                                {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($attendance->date)->format('l') }}
                                                </small>
                                            </td>
                                            <td>
                                                <strong>{{ $attendance->schedule->subject->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $attendance->schedule->classRoom->name }}</small>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($attendance->schedule->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($attendance->schedule->end_time)->format('H:i') }}
                                            </td>
                                            <td>
                                                @switch($attendance->status)
                                                    @case('hadir')
                                                        <span class="badge bg-success">Hadir</span>
                                                        @break
                                                    @case('sakit')
                                                        <span class="badge bg-info">Sakit</span>
                                                        @break
                                                    @case('izin')
                                                        <span class="badge bg-warning">Izin</span>
                                                        @break
                                                    @case('alpha')
                                                        <span class="badge bg-danger">Alpha</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $attendance->notes ?? '-' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer bg-white">
                            {{ $attendances->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada riwayat kehadiran</h5>
                            <p class="text-muted">Siswa ini belum memiliki catatan kehadiran di mata pelajaran Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-pink {
    background-color: #e83e8c !important;
}
</style>
@endpush
@endsection
