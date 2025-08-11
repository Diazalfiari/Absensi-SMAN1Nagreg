@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-eye me-2"></i>Detail Kelas: {{ $class->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.classes.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Kelas
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Basic Information -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Kelas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Nama Kelas</label>
                            <p class="mb-0">{{ $class->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Kode Kelas</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $class->code }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Tingkat</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $class->grade }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Kapasitas</label>
                            <p class="mb-0">{{ $class->capacity }} siswa</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Tahun Ajaran</label>
                            <p class="mb-0">{{ $class->academic_year }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Ruang Kelas</label>
                            <p class="mb-0">{{ $class->room ?? 'Belum ditentukan' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Wali Kelas</label>
                            <p class="mb-0">
                                @if($class->homeroomTeacher)
                                    {{ $class->homeroomTeacher->name }}
                                    <br><small class="text-muted">NIP: {{ $class->homeroomTeacher->nip }}</small>
                                @else
                                    <span class="text-muted">Belum ada wali kelas</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Status</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $class->is_active ? 'success' : 'danger' }}">
                                    {{ $class->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    @if($class->description)
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">Deskripsi</label>
                            <p class="mb-0">{{ $class->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistik Kelas</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-muted mb-0">Total Siswa</h6>
                        <h4 class="mb-0">{{ $students->count() }}</h4>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-muted mb-0">Kapasitas</h6>
                        <h4 class="mb-0">{{ $class->capacity }}</h4>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-chair fa-2x"></i>
                    </div>
                </div>
                
                <div class="progress">
                    <div class="progress-bar" role="progressbar" 
                         style="width: {{ $class->capacity > 0 ? ($students->count() / $class->capacity) * 100 : 0 }}%">
                        {{ $class->capacity > 0 ? round(($students->count() / $class->capacity) * 100) : 0 }}%
                    </div>
                </div>
                <small class="text-muted">Tingkat pengisian kelas</small>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit Kelas
                    </a>
                    <button type="button" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Tambah Siswa
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-calendar me-1"></i>Jadwal Kelas
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-chart-bar me-1"></i>Laporan Absensi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students List -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Siswa</h5>
                <button type="button" class="btn btn-sm btn-primary">
                    <i class="fas fa-user-plus me-1"></i>Tambah Siswa
                </button>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Kelamin</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->nis ?? 'Belum ada' }}</td>
                                <td>
                                    <div class="fw-bold">{{ $student->name }}</div>
                                    @if($student->user && $student->user->email)
                                        <small class="text-muted">{{ $student->user->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $student->gender == 'L' ? 'primary' : 'pink' }}">
                                        {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada siswa di kelas ini</h5>
                    <p class="text-muted">Klik "Tambah Siswa" untuk menambahkan siswa ke kelas ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Schedules -->
@if(isset($schedules) && $schedules->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Jadwal Pelajaran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Waktu</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->day }}</td>
                                <td>{{ $schedule->subject->name ?? 'N/A' }}</td>
                                <td>{{ $schedule->teacher->name ?? 'N/A' }}</td>
                                <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                <td>{{ $schedule->room ?? $class->room ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
