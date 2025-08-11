@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-history me-2"></i>Riwayat Absensi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('student.attendance') }}" class="btn btn-outline-primary">
                <i class="fas fa-camera me-1"></i>Kembali ke Absensi
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    @php
        $totalAttendances = $attendances->total();
        $hadirCount = $attendances->where('status', 'Hadir')->count();
        $sakitCount = $attendances->where('status', 'Sakit')->count();
        $izinCount = $attendances->where('status', 'Izin')->count();
        $alphaCount = $attendances->where('status', 'Alpha')->count();
        $attendancePercentage = $totalAttendances > 0 ? round(($hadirCount / $totalAttendances) * 100, 1) : 0;
    @endphp
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="display-6 fw-bold text-primary">{{ $totalAttendances }}</div>
                <div class="text-muted">Total Absensi</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="display-6 fw-bold text-success">{{ $hadirCount }}</div>
                <div class="text-muted">Hadir</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="display-6 fw-bold text-warning">{{ $sakitCount + $izinCount }}</div>
                <div class="text-muted">Sakit / Izin</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="display-6 fw-bold text-danger">{{ $alphaCount }}</div>
                <div class="text-muted">Alpha</div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Percentage -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="mb-3">Persentase Kehadiran</h4>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $attendancePercentage }}%" 
                         aria-valuenow="{{ $attendancePercentage }}" 
                         aria-valuemin="0" aria-valuemax="100">
                        {{ $attendancePercentage }}%
                    </div>
                </div>
                <div class="mt-2">
                    @if($attendancePercentage >= 90)
                        <span class="badge bg-success fs-6">Excellent</span>
                    @elseif($attendancePercentage >= 80)
                        <span class="badge bg-primary fs-6">Good</span>
                    @elseif($attendancePercentage >= 70)
                        <span class="badge bg-warning fs-6">Fair</span>
                    @else
                        <span class="badge bg-danger fs-6">Poor</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance History Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Riwayat Absensi Detail
                </h5>
            </div>
            <div class="card-body">
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                    <th>Waktu Submit</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $attendance->created_at->format('d/m/Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $attendance->created_at->format('l') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $attendance->schedule->subject->name }}</div>
                                        <small class="text-muted">{{ $attendance->schedule->classRoom->name }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ \Carbon\Carbon::parse($attendance->schedule->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($attendance->schedule->end_time)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->status === 'Hadir')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Hadir
                                            </span>
                                        @elseif($attendance->status === 'Sakit')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-thermometer-half me-1"></i>Sakit
                                            </span>
                                        @elseif($attendance->status === 'Izin')
                                            <span class="badge bg-info">
                                                <i class="fas fa-hand-paper me-1"></i>Izin
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Alpha
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $attendance->submitted_at ? $attendance->submitted_at->format('H:i') : '-' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $attendance->created_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($attendance->notes)
                                            <small class="text-muted">{{ $attendance->notes }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->photo_path && $attendance->status === 'Hadir')
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="showPhoto('{{ asset('storage/' . $attendance->photo_path) }}')"
                                                    title="Lihat Foto">
                                                <i class="fas fa-image"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
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
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada riwayat absensi</h5>
                        <p class="text-muted">Mulai absen untuk melihat riwayat kehadiran Anda</p>
                        <a href="{{ route('student.attendance') }}" class="btn btn-primary">
                            <i class="fas fa-camera me-1"></i>Mulai Absensi
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">
                    <i class="fas fa-image me-2"></i>Foto Absensi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="attendance-photo" src="" alt="Foto Absensi" class="img-fluid rounded" style="max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showPhoto(photoUrl) {
    document.getElementById('attendance-photo').src = photoUrl;
    new bootstrap.Modal(document.getElementById('photoModal')).show();
}
</script>
@endpush
