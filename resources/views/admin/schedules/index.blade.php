@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-calendar-alt me-2"></i>Jadwal Pelajaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Tambah Jadwal
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.schedules.export', ['format' => 'excel']) }}">
                        <i class="fas fa-file-excel me-1"></i>Excel (.xlsx)</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.schedules.export', ['format' => 'csv']) }}">
                        <i class="fas fa-file-csv me-1"></i>CSV (.csv)</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

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
                        <h5 class="card-title text-muted mb-0">Total Jadwal</h5>
                        <h3 class="mt-1 mb-0">{{ $totalSchedules }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-calendar-alt fa-2x"></i>
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
                        <h3 class="mt-1 mb-0">{{ $activeSchedules }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Total Mata Pelajaran</h5>
                        <h3 class="mt-1 mb-0">{{ $subjects->count() }}</h3>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-book fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Total Kelas</h5>
                        <h3 class="mt-1 mb-0">{{ $classes->count() }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <select class="form-select" id="filterClass">
                    <option value="">Pilih Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->name }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterDay">
                    <option value="">Pilih Hari</option>
                    @foreach($days as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterSubject">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary" onclick="filterSchedules()">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="resetFilter()">
                    <i class="fas fa-redo me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Class Selection for Grid View -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-table me-2"></i>Jadwal Pelajaran (Grid View)
        </h5>
        <div class="d-flex gap-2 align-items-center">
            <label class="form-label mb-0 me-2">Filter Kelas:</label>
            <select class="form-select form-select-sm" id="classFilter" style="width: 200px;" onchange="filterByClass()">
                <option value="">Semua Kelas</option>
                <!-- Options will be populated dynamically from schedule data -->
            </select>
        </div>
    </div>
</div>

<!-- Schedule Grid -->
<div class="card">
    <div class="card-body">
        @if($schedules->count() > 0)
            <!-- Legend -->
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading mb-2">
                    <i class="fas fa-info-circle me-2"></i>Petunjuk Penggunaan Grid Jadwal
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <small>
                            <strong>Format Grid:</strong><br>
                            • <strong>Baris</strong> = Jam Pelajaran<br>
                            • <strong>Kolom</strong> = Hari dalam Seminggu<br>
                            • <strong>Setiap Kotak</strong> = Jadwal Mata Pelajaran
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small>
                            <strong>Informasi Jadwal:</strong><br>
                            • 📚 Nama Mata Pelajaran<br>
                            • 👥 Kelas & 👨‍🏫 Guru Pengajar<br>
                            • 🚪 Ruangan (jika ada)
                        </small>
                    </div>
                </div>
            </div>
            <!-- Grid untuk semua kelas -->
            <div id="allClassesGrid">
                <div class="table-responsive">
                    <table class="table table-bordered schedule-grid">
                        <thead class="table-dark">
                            <tr>
                                <th width="10%" class="text-center">Jam</th>
                                <th width="15%" class="text-center">Senin</th>
                                <th width="15%" class="text-center">Selasa</th>
                                <th width="15%" class="text-center">Rabu</th>
                                <th width="15%" class="text-center">Kamis</th>
                                <th width="15%" class="text-center">Jumat</th>
                                <th width="15%" class="text-center">Sabtu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Static time slots for structure
                                $baseTimeSlots = [
                                    '07:00-07:45', '07:45-08:30', '08:30-09:15', '09:15-10:00',
                                    '10:15-11:00', '11:00-11:45', '11:45-12:30', '12:30-13:15',
                                    '13:15-14:00', '14:00-14:45', '14:45-15:30'
                                ];
                                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                
                                // Collect all actual time slots from database
                                $actualTimeSlots = [];
                                foreach($schedules as $schedule) {
                                    $startTime = date('H:i', strtotime($schedule->start_time));
                                    $endTime = date('H:i', strtotime($schedule->end_time));
                                    $timeKey = $startTime . '-' . $endTime;
                                    if (!in_array($timeKey, $actualTimeSlots)) {
                                        $actualTimeSlots[] = $timeKey;
                                    }
                                }
                                
                                // Merge and sort all time slots
                                $allTimeSlots = array_unique(array_merge($baseTimeSlots, $actualTimeSlots));
                                sort($allTimeSlots);
                                
                                // Group schedules by day and time
                                $scheduleGrid = [];
                                foreach($schedules as $schedule) {
                                    $startTime = date('H:i', strtotime($schedule->start_time));
                                    $endTime = date('H:i', strtotime($schedule->end_time));
                                    $timeKey = $startTime . '-' . $endTime;
                                    $scheduleGrid[$schedule->day][$timeKey][] = $schedule;
                                }
                            @endphp
                            
                            @foreach($allTimeSlots as $timeSlot)
                                <tr>
                                    <td class="fw-bold text-center bg-light">{{ $timeSlot }}</td>
                                    @foreach($days as $day)
                                        <td class="schedule-cell">
                                            @if(isset($scheduleGrid[$day][$timeSlot]))
                                                @foreach($scheduleGrid[$day][$timeSlot] as $schedule)
                                                    <div class="schedule-item mb-1 p-2 rounded border" 
                                                         data-class-id="{{ $schedule->class_id }}"
                                                         style="background-color: {{ $loop->index % 2 == 0 ? '#e3f2fd' : '#f3e5f5' }};">
                                                        <div class="fw-bold text-truncate" style="font-size: 0.85rem;">
                                                            {{ $schedule->subject->name ?? 'N/A' }}
                                                        </div>
                                                        <div class="text-muted small">
                                                            <i class="fas fa-users me-1"></i>{{ $schedule->classRoom->name ?? 'N/A' }}
                                                        </div>
                                                        <div class="text-muted small">
                                                            <i class="fas fa-user me-1"></i>{{ Str::limit($schedule->teacher->name ?? 'TBD', 20) }}
                                                        </div>
                                                        @if($schedule->room)
                                                            <div class="text-muted small">
                                                                <i class="fas fa-door-open me-1"></i>{{ $schedule->room }}
                                                            </div>
                                                        @endif
                                                        <div class="mt-1">
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a href="{{ route('admin.schedules.show', $schedule) }}" 
                                                                   class="btn btn-outline-info btn-sm" title="Lihat">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.schedules.edit', $schedule) }}" 
                                                                   class="btn btn-outline-warning btn-sm" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                        onclick="deleteSchedule({{ $schedule->id }}, '{{ $schedule->subject->name ?? 'N/A' }} - {{ $schedule->classRoom->name ?? 'N/A' }}')" 
                                                                        title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center text-muted py-3">
                                                    <small>-</small>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grid untuk kelas tertentu akan diloaded via AJAX atau dihandle di controller -->
            @if(isset($selectedClass))
                <div id="classGrid{{ $selectedClass->id }}" class="class-grid" style="display: none;">
                    <div class="mb-3">
                        <h5 class="text-primary">
                            <i class="fas fa-users me-2"></i>Jadwal Kelas {{ $selectedClass->name }}
                        </h5>
                        <small class="text-muted">Tahun Ajaran {{ $selectedClass->academic_year }}</small>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered schedule-grid">
                            <thead class="table-primary">
                                <tr>
                                    <th width="10%" class="text-center">Jam</th>
                                    <th width="15%" class="text-center">Senin</th>
                                    <th width="15%" class="text-center">Selasa</th>
                                    <th width="15%" class="text-center">Rabu</th>
                                    <th width="15%" class="text-center">Kamis</th>
                                    <th width="15%" class="text-center">Jumat</th>
                                    <th width="15%" class="text-center">Sabtu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $classSchedules = $schedules->where('class_id', $selectedClass->id);
                                    $classGrid = [];
                                    foreach($classSchedules as $schedule) {
                                        $timeKey = $schedule->start_time . '-' . $schedule->end_time;
                                        $classGrid[$schedule->day][$timeKey] = $schedule;
                                    }
                                @endphp
                                
                                @foreach($timeSlots as $timeSlot)
                                    <tr>
                                        <td class="fw-bold text-center bg-light">{{ $timeSlot }}</td>
                                        @foreach($days as $day)
                                            <td class="schedule-cell">
                                                @if(isset($classGrid[$day][$timeSlot]))
                                                    @php $schedule = $classGrid[$day][$timeSlot]; @endphp
                                                    <div class="schedule-item p-3 rounded border bg-primary text-white">
                                                        <div class="fw-bold mb-1">
                                                            {{ $schedule->subject->name ?? 'N/A' }}
                                                        </div>
                                                        <div class="small mb-1">
                                                            <i class="fas fa-user me-1"></i>{{ $schedule->teacher->name ?? 'TBD' }}
                                                        </div>
                                                        @if($schedule->room)
                                                            <div class="small mb-2">
                                                                <i class="fas fa-door-open me-1"></i>{{ $schedule->room }}
                                                            </div>
                                                        @endif
                                                        <div class="btn-group btn-group-sm w-100" role="group">
                                                            <a href="{{ route('admin.schedules.show', $schedule) }}" 
                                                               class="btn btn-outline-light btn-sm" title="Lihat">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('admin.schedules.edit', $schedule) }}" 
                                                               class="btn btn-outline-light btn-sm" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-outline-light btn-sm" 
                                                                    onclick="deleteSchedule({{ $schedule->id }}, '{{ $schedule->subject->name ?? 'N/A' }} - {{ $schedule->classRoom->name ?? 'N/A' }}')" 
                                                                    title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-center text-muted py-4">
                                                        <i class="fas fa-plus-circle fa-2x text-secondary opacity-50"></i>
                                                        <div class="small mt-1">Kosong</div>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada jadwal pelajaran</h5>
                <p class="text-muted">Klik tombol "Tambah Jadwal" untuk menambah jadwal pertama</p>
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah Jadwal Pertama
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
function filterByClass() {
    const classId = document.getElementById('classFilter').value;
    const scheduleItems = document.querySelectorAll('.schedule-item');

    scheduleItems.forEach(item => {
        const itemClassId = item.getAttribute('data-class-id');
        
        if (classId === '' || itemClassId === classId) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function deleteSchedule(id, name) {
    document.getElementById('scheduleName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/schedules/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function filterSchedules() {
    const classFilter = document.getElementById('filterClass').value;
    const dayFilter = document.getElementById('filterDay').value;
    const subjectFilter = document.getElementById('filterSubject').value;
    const scheduleItems = document.querySelectorAll('.schedule-item');

    scheduleItems.forEach(item => {
        const classId = item.getAttribute('data-class-id');
        const subjectName = item.querySelector('.fw-bold').textContent.trim();
        
        const matchesClass = !classFilter || classId === classFilter;
        const matchesSubject = !subjectFilter || subjectName.toLowerCase().includes(subjectFilter.toLowerCase());
        
        if (matchesClass && matchesSubject) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function resetFilter() {
    document.getElementById('filterClass').value = '';
    document.getElementById('filterDay').value = '';
    document.getElementById('filterSubject').value = '';
    document.getElementById('classFilter').value = '';
    
    const scheduleItems = document.querySelectorAll('.schedule-item');
    scheduleItems.forEach(item => {
        item.style.display = '';
    });
}

// Initialize grid view and populate class options
document.addEventListener('DOMContentLoaded', function() {
    // Populate class filter options from schedule items
    const classFilter = document.getElementById('classFilter');
    const scheduleItems = document.querySelectorAll('.schedule-item');
    const classIds = new Set();
    
    scheduleItems.forEach(item => {
        const classId = item.getAttribute('data-class-id');
        const className = item.querySelector('.text-muted').textContent.replace('🎓', '').trim();
        if (classId && className) {
            classIds.add(JSON.stringify({id: classId, name: className}));
        }
    });
    
    classIds.forEach(classData => {
        const classObj = JSON.parse(classData);
        const option = document.createElement('option');
        option.value = classObj.id;
        option.textContent = classObj.name;
        classFilter.appendChild(option);
    });
});
</script>

<style>
.schedule-grid {
    font-size: 0.9rem;
}

.schedule-grid th {
    background-color: #343a40 !important;
    color: white !important;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #495057 !important;
}

.schedule-grid td {
    vertical-align: top;
    height: 120px;
    padding: 8px;
    border: 1px solid #dee2e6;
}

.schedule-cell {
    position: relative;
    min-height: 100px;
}

.schedule-item {
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #ddd !important;
}

.schedule-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.schedule-item .fw-bold {
    color: #2c3e50;
    font-size: 0.85rem;
    line-height: 1.2;
}

.schedule-item .small {
    font-size: 0.75rem;
    line-height: 1.1;
}

.table-primary th {
    background-color: #0d6efd !important;
    color: white !important;
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.success {
    border-left: 4px solid #28a745;
}

.stat-card.info {
    border-left: 4px solid #17a2b8;
}

.stat-card.warning {
    border-left: 4px solid #ffc107;
}

@media (max-width: 768px) {
    .schedule-grid {
        font-size: 0.8rem;
    }
    
    .schedule-grid td {
        height: 80px;
        padding: 4px;
    }
    
    .schedule-item {
        padding: 8px !important;
    }
    
    .schedule-item .fw-bold {
        font-size: 0.75rem;
    }
    
    .schedule-item .small {
        font-size: 0.7rem;
    }
}
</style>
@endpush
