@extends('layouts.app')

@section('title', 'Laporan Kelas - ' . $class->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-chart-line me-2"></i>Laporan Kelas {{ $class->name }}
                    </h1>
                    <p class="text-muted">Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Print
                    </button>
                    <a href="{{ route('teacher.reports.export', $class->id) }}" class="btn btn-success">
                        <i class="fas fa-download me-1"></i>Export Excel
                    </a>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.reports.index') }}">Laporan Kelas</a></li>
                    <li class="breadcrumb-item active">{{ $class->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('teacher.reports.class', $class->id) }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" 
                                    value="{{ $startDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control" 
                                    value="{{ $endDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <select name="subject_id" class="form-select">
                                    <option value="">Semua Mata Pelajaran</option>
                                    @foreach($schedules->unique('subject_id') as $schedule)
                                        <option value="{{ $schedule->subject->id }}" 
                                            {{ request('subject_id') == $schedule->subject->id ? 'selected' : '' }}>
                                            {{ $schedule->subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('teacher.reports.class', $class->id) }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-white fw-bold">Total Siswa</h6>
                            <h3 class="mb-0 text-white fw-bold">{{ $classStats['total_students'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-white fw-bold">Total Hadir</h6>
                            <h3 class="mb-0 text-white fw-bold">{{ $classStats['hadir'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-dark fw-bold">Sakit / Izin</h6>
                            <h3 class="mb-0 text-dark fw-bold">{{ $classStats['sakit'] + $classStats['izin'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-white fw-bold">Alpha</h6>
                            <h3 class="mb-0 text-white fw-bold">{{ $classStats['alpha'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Rate Chart -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-area me-2"></i>Grafik Tingkat Kehadiran</h6>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribusi Kehadiran</h6>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" height="150"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tingkat Kehadiran</span>
                            <strong class="text-primary">{{ $classStats['attendance_rate'] }}%</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $classStats['attendance_rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Attendance Report -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table me-2"></i>Laporan Kehadiran Siswa
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if(count($reportData) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th class="text-center">Hadir</th>
                                        <th class="text-center">Sakit</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Alpha</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Persentase</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData as $studentId => $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $data['student']->nis }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($data['student']->photo)
                                                        <img src="{{ asset('storage/' . $data['student']->photo) }}" 
                                                             class="rounded-circle me-2" width="32" height="32"
                                                             alt="{{ $data['student']->user->name }}">
                                                    @else
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px; font-size: 12px;">
                                                            {{ strtoupper(substr($data['student']->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $data['student']->user->name }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $data['hadir'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $data['sakit'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">{{ $data['izin'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $data['alpha'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $data['total'] }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="progress me-2" style="width: 50px; height: 8px;">
                                                        <div class="progress-bar {{ $data['percentage'] >= 80 ? 'bg-success' : ($data['percentage'] >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                                             style="width: {{ $data['percentage'] }}%"></div>
                                                    </div>
                                                    <small><strong>{{ $data['percentage'] }}%</strong></small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($data['percentage'] >= 80)
                                                    <span class="badge bg-success">Baik</span>
                                                @elseif($data['percentage'] >= 60)
                                                    <span class="badge bg-warning">Perhatian</span>
                                                @else
                                                    <span class="badge bg-danger">Bermasalah</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-center">{{ $classStats['hadir'] }}</th>
                                        <th class="text-center">{{ $classStats['sakit'] }}</th>
                                        <th class="text-center">{{ $classStats['izin'] }}</th>
                                        <th class="text-center">{{ $classStats['alpha'] }}</th>
                                        <th class="text-center">{{ $classStats['total_attendances'] }}</th>
                                        <th class="text-center">
                                            <strong>{{ $classStats['attendance_rate'] }}%</strong>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada data kehadiran</h5>
                            <p class="text-muted">Belum ada catatan kehadiran untuk periode yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn, .breadcrumb, .card-header .btn {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .container-fluid {
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
}

/* Enhanced Chart Styling */
#attendanceChart, #pieChart {
    background-color: white !important;
    border-radius: 8px;
}

.card-body canvas {
    background: white !important;
    border-radius: 8px;
}

/* Ensure legend text is visible */
.chartjs-legend {
    color: #333 !important;
}

/* Progress bar enhancement */
.progress {
    background-color: rgba(0,0,0,0.1) !important;
}

.progress-bar {
    font-weight: bold;
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

/* Enhanced Card Styling */
.card {
    border: none !important;
    border-radius: 10px !important;
}

.bg-primary {
    background-color: #4e73df !important;
}

.bg-success {
    background-color: #1cc88a !important;
}

.bg-warning {
    background-color: #f6c23e !important;
}

.bg-danger {
    background-color: #e74a3b !important;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.fw-bold {
    font-weight: 700 !important;
}

.text-white-50 {
    opacity: 0.6;
}

.text-dark {
    color: #333 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Debug logging
console.log('Chart.js loaded:', typeof Chart !== 'undefined');
console.log('Class Stats:', @json($classStats));

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing charts...');
    
    // Enhanced Attendance Chart with debug
    const attendanceCtx = document.getElementById('attendanceChart');
    if (attendanceCtx) {
        console.log('Found attendance chart canvas');
        
        const ctx = attendanceCtx.getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Tingkat Kehadiran (%)',
                    data: [85, 88, 82, 90],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.3)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            },
                            color: '#333',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#333',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#4e73df',
                        borderWidth: 2
                    }
                }
            }
        });
        
        console.log('Attendance chart created:', attendanceChart);
    } else {
        console.error('Attendance chart canvas not found!');
    }

    // Enhanced Pie Chart with debug
    const pieCtx = document.getElementById('pieChart');
    if (pieCtx) {
        console.log('Found pie chart canvas');
        
        const ctx2 = pieCtx.getContext('2d');
        const pieChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
                datasets: [{
                    data: [{{ $classStats['hadir'] }}, {{ $classStats['sakit'] }}, {{ $classStats['izin'] }}, {{ $classStats['alpha'] }}],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                    borderColor: ['#fff', '#fff', '#fff', '#fff'],
                    borderWidth: 2,
                    hoverBackgroundColor: ['#218838', '#e0a800', '#138496', '#c82333'],
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            color: '#333',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#fff',
                        borderWidth: 1
                    }
                }
            }
        });
        
        console.log('Pie chart created:', pieChart);
        console.log('Pie chart data:', pieChart.data.datasets[0].data);
    } else {
        console.error('Pie chart canvas not found!');
    }
});

// Functions for export and print
function exportClassReport(classId) {
    alert('Export functionality will be implemented soon');
}

function printClassReport(classId) {
    window.print();
}
</script>
@endpush
@endsection
