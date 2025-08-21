@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-clipboard-check me-2"></i>Absensi Siswa</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                <h4 class="fw-bold text-success">{{ $attendanceStats['hadir'] }}</h4>
                <p class="text-muted mb-0">Hadir</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-thermometer-half text-warning fa-2x mb-2"></i>
                <h4 class="fw-bold text-warning">{{ $attendanceStats['sakit'] }}</h4>
                <p class="text-muted mb-0">Sakit</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-hand-paper text-info fa-2x mb-2"></i>
                <h4 class="fw-bold text-info">{{ $attendanceStats['izin'] }}</h4>
                <p class="text-muted mb-0">Izin</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-times-circle text-danger fa-2x mb-2"></i>
                <h4 class="fw-bold text-danger">{{ $attendanceStats['alpha'] }}</h4>
                <p class="text-muted mb-0">Alpha</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('teacher.attendances.index') }}">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Mata Pelajaran</label>
                            <select name="schedule_id" class="form-select">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" 
                                            {{ request('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->subject->name }} - {{ $schedule->classRoom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Attendance List -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Data Absensi</h5>
            </div>
            <div class="card-body">
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Waktu Submit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $attendance->date->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $attendance->date->format('l') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->student->user->name) }}&size=32" 
                                                 class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div class="fw-semibold">{{ $attendance->student->user->name }}</div>
                                                <small class="text-muted">{{ $attendance->student->student_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->schedule->subject->name }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $attendance->schedule->classRoom->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $attendance->getStatusColor() }}">
                                            {{ $attendance->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->check_in)
                                            <div class="fw-semibold">{{ $attendance->check_in->format('H:i') }}</div>
                                            <small class="text-muted">{{ $attendance->check_in->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('teacher.attendances.show', $attendance->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendances->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Data Absensi</h5>
                        <p class="text-muted">Data absensi siswa akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
