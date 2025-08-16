@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-book me-2"></i>Mata Pelajaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Tambah Mata Pelajaran
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.subjects.export', ['format' => 'excel']) }}">
                        <i class="fas fa-file-excel me-1"></i>Excel (.xlsx)</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.subjects.export', ['format' => 'csv']) }}">
                        <i class="fas fa-file-csv me-1"></i>CSV (.csv)</a></li>
                </ul>
            </div>
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

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total Mata Pelajaran</h5>
                        <h3 class="mt-1 mb-0">{{ $totalSubjects }}</h3>
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
                        <h5 class="card-title text-muted mb-0">Mata Pelajaran Wajib</h5>
                        <h3 class="mt-1 mb-0">{{ $wajibCount }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-bookmark fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Mata Pelajaran Peminatan</h5>
                        <h3 class="mt-1 mb-0">{{ $peminatanCount }}</h3>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-star fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Muatan Lokal</h5>
                        <h3 class="mt-1 mb-0">{{ $mulokCount }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subjects Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Mata Pelajaran</h5>
        <div class="d-flex gap-2">
            <input type="text" class="form-control form-control-sm" placeholder="Cari mata pelajaran..." style="width: 200px;">
            <select class="form-select form-select-sm" style="width: 150px;">
                <option value="">Semua Kategori</option>
                <option value="Wajib">Wajib</option>
                <option value="Peminatan">Peminatan</option>
                <option value="Muatan Lokal">Muatan Lokal</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        @if($subjects->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="10%">Kode</th>
                            <th width="25%">Nama Mata Pelajaran</th>
                            <th width="15%">Kategori</th>
                            <th width="10%">Jam/Minggu</th>
                            <th width="20%">Deskripsi</th>
                            <th width="10%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td>
                                <span class="badge bg-primary">{{ $subject->code }}</span>
                            </td>
                            <td>
                                <strong>{{ $subject->name }}</strong>
                            </td>
                            <td>
                                @if($subject->category === 'Wajib')
                                    <span class="badge bg-success">{{ $subject->category }}</span>
                                @elseif($subject->category === 'Peminatan')
                                    <span class="badge bg-info">{{ $subject->category }}</span>
                                @else
                                    <span class="badge bg-warning">{{ $subject->category }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted">{{ $subject->credit_hours }} Jam</span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $subject->description ? Str::limit($subject->description, 50) : '-' }}
                                </small>
                            </td>
                            <td>
                                @if($subject->schedules->count() > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-minus-circle me-1"></i>Belum Dijadwalkan
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.subjects.show', $subject) }}" 
                                       class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subjects.edit', $subject) }}" 
                                       class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="deleteSubject({{ $subject->id }}, '{{ $subject->name }}')" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($subjects->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $subjects->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-book fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada data mata pelajaran</h5>
                <p class="text-muted">Klik tombol "Tambah Mata Pelajaran" untuk menambah data pertama</p>
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah Mata Pelajaran Pertama
                </a>
            </div>
        @endif
    </div>
</div>

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

// Live search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[placeholder="Cari mata pelajaran..."]');
    const categoryFilter = document.querySelector('select');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;

        tableRows.forEach(row => {
            const nameCell = row.querySelector('td:nth-child(2)');
            const categoryCell = row.querySelector('td:nth-child(3)');
            
            if (nameCell && categoryCell) {
                const name = nameCell.textContent.toLowerCase();
                const category = categoryCell.textContent.trim();
                
                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = !selectedCategory || category === selectedCategory;
                
                row.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterTable);
    }
});
</script>
@endpush
