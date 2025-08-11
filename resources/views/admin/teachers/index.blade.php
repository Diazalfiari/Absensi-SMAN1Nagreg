@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-chalkboard-teacher me-2"></i>Data Guru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Tambah Guru
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.teachers.export', ['format' => 'xlsx']) }}">
                        <i class="fas fa-file-excel me-2"></i>Export Excel (.xlsx)
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.teachers.export', ['format' => 'csv']) }}">
                        <i class="fas fa-file-csv me-2"></i>Export CSV (.csv)
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.teachers.export.google') }}">
                        <i class="fab fa-google me-2"></i>Export untuk Google Sheets
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total Guru</h5>
                        <h3 class="mt-1 mb-0">{{ $totalTeachers }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Guru Aktif</h5>
                        <h3 class="mt-1 mb-0">{{ $activeTeachers ?? $teachers->where('status', 'active')->count() }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Guru Tidak Aktif</h5>
                        <h3 class="mt-1 mb-0">{{ $inactiveTeachers ?? $teachers->where('status', 'inactive')->count() }}</h3>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-user-times fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Teachers Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Daftar Guru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Mata Pelajaran</th>
                        <th>Jenis Kelamin</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr>
                        <td>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}" 
                                 class="rounded-circle" width="40" height="40" alt="Avatar">
                        </td>
                        <td>{{ $teacher->nip ?? '-' }}</td>
                        <td>
                            <div class="fw-bold">{{ $teacher->name }}</div>
                            <small class="text-muted">{{ $teacher->email }}</small>
                        </td>
                        <td>
                            @if($teacher->subjects && $teacher->subjects->count() > 0)
                                @foreach($teacher->subjects as $subject)
                                    <span class="badge bg-info me-1">{{ $subject->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Belum ada mata pelajaran</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $teacher->gender == 'L' ? 'info' : 'secondary' }}" style="{{ $teacher->gender == 'P' ? 'background-color: #ff69b4 !important;' : '' }}">
                                {{ $teacher->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td>{{ $teacher->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $teacher->status == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($teacher->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" 
                                        onclick="confirmDelete('{{ $teacher->id }}', '{{ $teacher->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data guru</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($teachers) && method_exists($teachers, 'links'))
        <div class="d-flex justify-content-center mt-3">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data guru <strong id="teacherName"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(teacherId, teacherName) {
    document.getElementById('teacherName').textContent = teacherName;
    document.getElementById('deleteForm').action = `/admin/teachers/${teacherId}`;
    
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Show success/error messages
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = `
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.d-flex'));
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.d-flex'));
    });
@endif

@if(session('export_info'))
    document.addEventListener('DOMContentLoaded', function() {
        const exportInfo = @json(session('export_info'));
        // Create export info modal
        const modalHTML = `
            <div class="modal fade" id="exportInfoModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-check-circle me-2"></i>${exportInfo.title}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">${exportInfo.message}</p>
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Langkah-langkah Upload ke Google Sheets:</h6>
                                <ol class="mb-0">
                                    ${exportInfo.steps.map(step => `<li>${step}</li>`).join('')}
                                </ol>
                            </div>
                            <div class="text-center mt-3">
                                <a href="https://drive.google.com" target="_blank" class="btn btn-primary">
                                    <i class="fab fa-google-drive me-1"></i>Buka Google Drive
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        const exportModal = new bootstrap.Modal(document.getElementById('exportInfoModal'));
        exportModal.show();
    });
@endif
</script>
@endpush
