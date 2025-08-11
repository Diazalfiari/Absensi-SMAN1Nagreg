@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-school me-2"></i>Data Kelas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.classes.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Tambah Kelas
            </a>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.classes.export') }}">
                        <i class="fas fa-file-excel text-success me-2"></i>Export Excel
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.classes.export') }}?format=csv">
                        <i class="fas fa-file-csv text-info me-2"></i>Export CSV
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="window.print()">
                        <i class="fas fa-print text-secondary me-2"></i>Print
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total Kelas</h5>
                        <h3 class="mt-1 mb-0">{{ $totalClasses }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-school fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Kelas X</h5>
                        <h3 class="mt-1 mb-0">{{ $classes->where('grade', 'X')->count() }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-graduation-cap fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Kelas XI</h5>
                        <h3 class="mt-1 mb-0">{{ $classes->where('grade', 'XI')->count() }}</h3>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-graduation-cap fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Kelas XII</h5>
                        <h3 class="mt-1 mb-0">{{ $classes->where('grade', 'XII')->count() }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-graduation-cap fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Classes Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Daftar Kelas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode Kelas</th>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Wali Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Ruang Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                    <tr>
                        <td>
                            <span class="badge bg-primary">{{ $class->code }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $class->name }}</div>
                            <small class="text-muted">{{ $class->description ?? 'Tidak ada deskripsi' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $class->grade }}</span>
                        </td>
                        <td>
                            @if($class->homeroomTeacher)
                                <div class="fw-bold">{{ $class->homeroomTeacher->name }}</div>
                                <small class="text-muted">{{ $class->homeroomTeacher->nip }}</small>
                            @else
                                <span class="text-muted">Belum ada wali kelas</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $class->students_count ?? 0 }} siswa</span>
                        </td>
                        <td>{{ $class->room ?? 'Belum ditentukan' }}</td>
                        <td>
                            <span class="badge bg-{{ $class->status == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($class->status ?? 'active') }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" 
                                        onclick="confirmDelete('{{ $class->id }}', '{{ $class->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-school fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data kelas</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $classes->links() }}
        </div>
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
                <p>Apakah Anda yakin ingin menghapus kelas <strong id="className"></strong>?</p>
                <p class="text-danger"><strong>⚠️ PERINGATAN: Tindakan ini akan menghapus data secara permanen dan tidak dapat dibatalkan!</strong></p>
                <p class="text-muted"><small>Data kelas akan dihapus dari database secara permanen.</small></p>
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

@push('styles')
<style>
@media print {
    .btn-toolbar, .btn-group, .pagination, .alert {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    body {
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(classId, className) {
    document.getElementById('className').textContent = className;
    document.getElementById('deleteForm').action = `{{ route('admin.classes.index') }}/${classId}`;
    
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
</script>
@endpush
