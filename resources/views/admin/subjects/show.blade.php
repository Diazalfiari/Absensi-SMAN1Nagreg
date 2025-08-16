@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-eye me-2"></i>Detail Mata Pelajaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <button type="button" class="btn btn-sm btn-danger" 
                    onclick="deleteSubject({{ $subject->id }}, '{{ $subject->name }}')">
                <i class="fas fa-trash me-1"></i>Hapus
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Subject Information Card -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-book me-2"></i>Informasi Mata Pelajaran
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Mata Pelajaran</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-primary fs-6">{{ $subject->code }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <div class="form-control-plaintext">
                                @if($subject->category === 'Wajib')
                                    <span class="badge bg-success fs-6">{{ $subject->category }}</span>
                                @elseif($subject->category === 'Peminatan')
                                    <span class="badge bg-info fs-6">{{ $subject->category }}</span>
                                @else
                                    <span class="badge bg-warning fs-6">{{ $subject->category }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Mata Pelajaran</label>
                            <div class="form-control-plaintext">
                                <h4 class="text-primary mb-0">{{ $subject->name }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jam Pelajaran per Minggu</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-secondary fs-6">{{ $subject->credit_hours }} Jam</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Jadwal</label>
                            <div class="form-control-plaintext">
                                @if($subject->schedules && $subject->schedules->count() > 0)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>Terjadwal ({{ $subject->schedules->count() }} jadwal)
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-minus-circle me-1"></i>Belum Dijadwalkan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <div class="form-control-plaintext">
                                <p class="mb-0">{{ $subject->description ?: 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Timestamps -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Dibuat</label>
                            <div class="form-control-plaintext">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $subject->created_at->format('d/m/Y') }} 
                                    <i class="fas fa-clock me-1 ms-2"></i>
                                    {{ $subject->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Terakhir Diubah</label>
                            <div class="form-control-plaintext">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $subject->updated_at->format('d/m/Y') }} 
                                    <i class="fas fa-clock me-1 ms-2"></i>
                                    {{ $subject->updated_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics and Actions Card -->
    <div class="col-lg-4">
        <!-- Statistics Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistik
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Jadwal</span>
                    <span class="badge bg-primary">{{ $subject->schedules ? $subject->schedules->count() : 0 }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Jam per Minggu</span>
                    <span class="badge bg-info">{{ $subject->credit_hours }} Jam</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span>Kategori</span>
                    @if($subject->category === 'Wajib')
                        <span class="badge bg-success">{{ $subject->category }}</span>
                    @elseif($subject->category === 'Peminatan')
                        <span class="badge bg-info">{{ $subject->category }}</span>
                    @else
                        <span class="badge bg-warning">{{ $subject->category }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Mata Pelajaran
                    </a>
                    
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>Lihat Semua Mata Pelajaran
                    </a>
                    
                    <a href="{{ route('admin.subjects.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Mata Pelajaran Baru
                    </a>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="deleteSubject({{ $subject->id }}, '{{ $subject->name }}')">
                        <i class="fas fa-trash me-2"></i>Hapus Mata Pelajaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedules Section (if any) -->
@if($subject->schedules && $subject->schedules->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Jadwal Mata Pelajaran
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Guru</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject->schedules as $schedule)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $schedule->classRoom->name ?? ($schedule->class->name ?? 'N/A') }}</span>
                                </td>
                                <td>{{ $schedule->day ?? 'N/A' }}</td>
                                <td>
                                    {{ $schedule->start_time ?? 'N/A' }} - {{ $schedule->end_time ?? 'N/A' }}
                                </td>
                                <td>{{ $schedule->teacher->name ?? 'Belum ditentukan' }}</td>
                                <td>
                                    @if(isset($schedule->is_active) ? $schedule->is_active : true)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
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
                <p>Apakah Anda yakin ingin menghapus mata pelajaran <strong id="subjectName"></strong>?</p>
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
function deleteSubject(id, name) {
    document.getElementById('subjectName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/subjects/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
