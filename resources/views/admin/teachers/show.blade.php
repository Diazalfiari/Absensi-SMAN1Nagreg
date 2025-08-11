@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-chalkboard-teacher me-2"></i>Detail Guru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Teacher Profile Card -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}" 
                     class="rounded-circle mb-3" width="120" height="120" alt="Teacher Photo">
                <h5 class="card-title">{{ $teacher->name }}</h5>
                <p class="text-muted">{{ $teacher->nip }}</p>
                <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'danger' }} fs-6">
                    {{ ucfirst($teacher->status) }}
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
                                <td>{{ $teacher->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">NIP:</td>
                                <td>{{ $teacher->nip }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>{{ $teacher->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jenis Kelamin:</td>
                                <td>{{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">No. HP:</td>
                                <td>{{ $teacher->phone ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Tempat, Tanggal Lahir:</td>
                                <td>{{ $teacher->birth_place }}, {{ \Carbon\Carbon::parse($teacher->birth_date)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Pendidikan:</td>
                                <td>{{ $teacher->education_level }} {{ $teacher->major }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal Mulai Mengajar:</td>
                                <td>{{ \Carbon\Carbon::parse($teacher->hire_date)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Pengalaman Mengajar:</td>
                                <td>{{ \Carbon\Carbon::parse($teacher->hire_date)->diffInYears(now()) }} tahun</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Alamat:</td>
                                <td>{{ $teacher->address }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subjects Taught -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Mata Pelajaran yang Diampu</h5>
            </div>
            <div class="card-body">
                @if($teacher->subjects && $teacher->subjects->count() > 0)
                    <div class="row">
                        @foreach($teacher->subjects as $subject)
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-2x text-primary mb-2"></i>
                                    <h6 class="card-title">{{ $subject->name }}</h6>
                                    <p class="card-text text-muted">{{ $subject->code }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Belum ada mata pelajaran yang diampu</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Teaching Schedule -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Jadwal Mengajar</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($schedules) && $schedules->count() > 0)
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->day ?? 'Senin' }}</td>
                                    <td>{{ $schedule->subject->name ?? '-' }}</td>
                                    <td>{{ $schedule->classRoom->name ?? '-' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($schedule->start_time ?? '07:00')->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time ?? '08:00')->format('H:i') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $schedule->is_active ? 'success' : 'danger' }}">
                                            {{ $schedule->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Belum ada jadwal mengajar</h6>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Teaching Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Mata Pelajaran</h5>
                        <h3 class="mt-1 mb-0">{{ $teacher->subjects->count() }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-book fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Jadwal Aktif</h5>
                        <h3 class="mt-1 mb-0">{{ isset($schedules) ? $schedules->where('is_active', true)->count() : 0 }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Pengalaman</h5>
                        <h3 class="mt-1 mb-0">{{ \Carbon\Carbon::parse($teacher->hire_date)->diffInYears(now()) }}</h3>
                        <small class="text-muted">Tahun</small>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-medal fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Status</h5>
                        <h3 class="mt-1 mb-0 text-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($teacher->status) }}
                        </h3>
                    </div>
                    <div class="text-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                        <i class="fas fa-{{ $teacher->status === 'active' ? 'user-check' : 'user-times' }} fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
