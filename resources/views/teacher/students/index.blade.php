@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-users me-2"></i>Data Siswa
                    </h1>
                    <p class="text-muted">Kelola data siswa yang Anda ajar</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Siswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('teacher.students.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Kelas</label>
                                <select name="class_id" class="form-select">
                                    <option value="">Semua Kelas</option>
                                    @foreach($teachingClasses as $class)
                                        <option value="{{ $class->id }}" 
                                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cari Siswa</label>
                                <input type="text" name="search" class="form-control" 
                                    placeholder="Nama atau NIS siswa..." 
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Siswa</h5>
                            <h2 class="mb-0">{{ $students->total() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Kelas Diajar</h5>
                            <h2 class="mb-0">{{ $teachingClasses->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chalkboard fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Siswa Aktif</h5>
                            <h2 class="mb-0">{{ $students->where('status', 'active')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Perlu Perhatian</h5>
                            <h2 class="mb-0">3</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Daftar Siswa
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Status</th>
                                        <th>Tingkat Kehadiran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td>{{ $students->firstItem() + $index }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $student->nis }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        @if($student->photo)
                                                            <img src="{{ asset('storage/' . $student->photo) }}" 
                                                                 class="rounded-circle" width="40" height="40"
                                                                 alt="{{ $student->user->name }}">
                                                        @else
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px;">
                                                                {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $student->user->name }}</h6>
                                                        <small class="text-muted">{{ $student->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $student->classRoom->name }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $student->gender == 'L' ? 'bg-primary' : 'bg-pink' }}">
                                                    {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $student->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($student->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $attendanceRate = rand(75, 98); // Dummy data, replace with actual calculation
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 60px; height: 8px;">
                                                        <div class="progress-bar {{ $attendanceRate >= 80 ? 'bg-success' : ($attendanceRate >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                                             style="width: {{ $attendanceRate }}%"></div>
                                                    </div>
                                                    <small class="text-muted">{{ $attendanceRate }}%</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('teacher.students.show', $student->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-success" 
                                                            onclick="showAttendanceModal({{ $student->id }})" title="Absensi">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-info" 
                                                            onclick="showReportModal({{ $student->id }})" title="Laporan">
                                                        <i class="fas fa-chart-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} 
                                    dari {{ $students->total() }} siswa
                                </div>
                                {{ $students->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data siswa</h5>
                            <p class="text-muted">Belum ada siswa yang terdaftar atau sesuai filter yang dipilih.</p>
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
.avatar-sm {
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script>
function showAttendanceModal(studentId) {
    // Implement attendance modal
    alert('Fitur absensi untuk siswa ID: ' + studentId + ' akan segera tersedia');
}

function showReportModal(studentId) {
    // Implement report modal  
    alert('Fitur laporan untuk siswa ID: ' + studentId + ' akan segera tersedia');
}
</script>
@endpush
@endsection
