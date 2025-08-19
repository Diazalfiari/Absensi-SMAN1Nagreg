@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>Detail Jadwal Pelajaran
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit me-1"></i>Edit Jadwal
            </a>
            <button type="button" class="btn btn-sm btn-danger" 
                    onclick="deleteSchedule({{ $schedule->id }}, '{{ $schedule->subject->name ?? 'N/A' }} - {{ $schedule->classRoom->name ?? 'N/A' }}')" 
                    title="Hapus Jadwal">
                <i class="fas fa-trash me-1"></i>Hapus
            </button>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Schedule Details Card -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Jadwal
                </h5>
                <span class="badge {{ $schedule->is_active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $schedule->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informasi Dasar</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mata Pelajaran:</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-book text-primary me-2"></i>
                                <div>
                                    <span class="fw-semibold">{{ $schedule->subject->name ?? 'N/A' }}</span>
                                    @if($schedule->subject && $schedule->subject->code)
                                        <small class="text-muted d-block">[{{ $schedule->subject->code }}]</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kelas:</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users text-info me-2"></i>
                                <div>
                                    <span class="fw-semibold">{{ $schedule->classRoom->name ?? 'N/A' }}</span>
                                    @if($schedule->classRoom && $schedule->classRoom->grade)
                                        <small class="text-muted d-block">Tingkat {{ $schedule->classRoom->grade }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Guru Pengajar:</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-tie text-warning me-2"></i>
                                <div>
                                    <span class="fw-semibold">{{ $schedule->teacher->name ?? 'Belum ditentukan' }}</span>
                                    @if($schedule->teacher && $schedule->teacher->email)
                                        <small class="text-muted d-block">{{ $schedule->teacher->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informasi Waktu</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari:</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar text-success me-2"></i>
                                <span class="badge bg-primary fs-6">{{ $schedule->day }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Waktu:</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-danger me-2"></i>
                                <div>
                                    <span class="fw-semibold">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                    @php
                                        $duration = \Carbon\Carbon::parse($schedule->start_time)->diffInMinutes(\Carbon\Carbon::parse($schedule->end_time));
                                    @endphp
                                    <small class="text-muted d-block">Durasi: {{ $duration }} menit</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ruangan:</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-door-open text-secondary me-2"></i>
                                <span class="fw-semibold">{{ $schedule->room ?: 'Ruang Kelas Regular' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted mb-3">Informasi Akademik</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tahun Ajaran:</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-graduation-cap text-info me-2"></i>
                                        <span class="fw-semibold">{{ $schedule->academic_year }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Semester:</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-list-ol text-success me-2"></i>
                                        <span class="fw-semibold">
                                            Semester {{ $schedule->semester }} 
                                            ({{ $schedule->semester == 1 ? 'Ganjil' : 'Genap' }})
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status:</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-toggle-{{ $schedule->is_active ? 'on text-success' : 'off text-secondary' }} me-2"></i>
                                        <span class="fw-semibold">{{ $schedule->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics & Quick Actions -->
    <div class="col-lg-4">
        <!-- Class Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Informasi Kelas
                </h6>
            </div>
            <div class="card-body">
                @if($schedule->classRoom)
                    <div class="mb-3">
                        <small class="text-muted">Nama Kelas:</small>
                        <div class="fw-bold">{{ $schedule->classRoom->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Kapasitas:</small>
                        <div class="fw-bold">
                            {{ $students->count() ?? 0 }} / {{ $schedule->classRoom->capacity ?? 'N/A' }} Siswa
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Wali Kelas:</small>
                        <div class="fw-bold">{{ $schedule->classRoom->homeroomTeacher->name ?? 'Belum ditentukan' }}</div>
                    </div>
                @else
                    <p class="text-muted">Informasi kelas tidak tersedia</p>
                @endif
            </div>
        </div>

        <!-- Subject Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-book me-2"></i>Informasi Mata Pelajaran
                </h6>
            </div>
            <div class="card-body">
                @if($schedule->subject)
                    <div class="mb-3">
                        <small class="text-muted">Nama Mata Pelajaran:</small>
                        <div class="fw-bold">{{ $schedule->subject->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Kode:</small>
                        <div class="fw-bold">{{ $schedule->subject->code ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">SKS:</small>
                        <div class="fw-bold">{{ $schedule->subject->credit_hours ?? '-' }} SKS</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Kategori:</small>
                        <div class="fw-bold">
                            <span class="badge {{ $schedule->subject->category == 'Wajib' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $schedule->subject->category ?? 'Tidak ditentukan' }}
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-muted">Informasi mata pelajaran tidak tersedia</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Tindakan Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($schedule->classRoom)
                        <a href="{{ route('admin.classes.show', $schedule->classRoom) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye me-1"></i>Lihat Detail Kelas
                        </a>
                    @endif
                    
                    @if($schedule->subject)
                        <a href="{{ route('admin.subjects.show', $schedule->subject) }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-book me-1"></i>Lihat Detail Mata Pelajaran
                        </a>
                    @endif
                    
                    <a href="{{ route('admin.schedules.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Tambah Jadwal Baru
                    </a>
                    
                    <a href="{{ route('admin.schedules.export', ['format' => 'excel']) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-download me-1"></i>Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students List if Available -->
@if($students && $students->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h6 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>Daftar Siswa di Kelas ({{ $students->count() }} siswa)
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Nama Siswa</th>
                        <th width="15%">NIS</th>
                        <th width="15%">NISN</th>
                        <th width="20%">Email</th>
                        <th width="10%">Jenis Kelamin</th>
                        <th width="15%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="rounded-circle" width="32" height="32">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $student->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->nis }}</td>
                        <td>{{ $student->nisn }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <span class="badge {{ $student->gender == 'L' ? 'bg-info' : 'bg-warning' }}">
                                {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $student->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Recent Attendances if Available -->
@if($recentAttendances && $recentAttendances->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h6 class="card-title mb-0">
            <i class="fas fa-history me-2"></i>Presensi Terbaru ({{ $recentAttendances->count() }} record)
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Nama Siswa</th>
                        <th width="15%">Waktu Masuk</th>
                        <th width="15%">Waktu Keluar</th>
                        <th width="15%">Status</th>
                        <th width="15%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentAttendances as $attendance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                        <td>{{ $attendance->student->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                        <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                        <td>
                            <span class="badge 
                                @switch($attendance->status)
                                    @case('hadir') bg-success @break
                                    @case('terlambat') bg-warning @break
                                    @case('sakit') bg-info @break
                                    @case('izin') bg-secondary @break
                                    @case('alpha') bg-danger @break
                                    @default bg-light text-dark
                                @endswitch
                            ">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td>{{ $attendance->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <p>Apakah Anda yakin ingin menghapus jadwal <strong id="scheduleName"></strong>?</p>
                <div class="alert alert-warning">
                    <small><i class="fas fa-info-circle"></i> Data yang sudah dihapus tidak dapat dikembalikan!</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function deleteSchedule(id, name) {
    document.getElementById('scheduleName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/schedules/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
