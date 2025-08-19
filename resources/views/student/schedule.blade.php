@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-calendar-alt me-2"></i>Jadwal Pelajaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="badge bg-primary fs-6">
                <i class="fas fa-graduation-cap me-1"></i>{{ $student->classRoom->name ?? 'Belum Ada Kelas' }}
            </span>
        </div>
    </div>
</div>

<!-- Weekly Schedule -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-calendar-week me-2"></i>Jadwal Mingguan
                    </h5>
                    <div class="badge bg-light text-primary px-3 py-1">
                        <i class="fas fa-calendar-day me-1"></i>{{ now()->format('d M Y') }}
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                @if(isset($weeklySchedules) && $weeklySchedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 110px; vertical-align: middle;">
                                        <i class="fas fa-clock me-1"></i>Waktu
                                    </th>
                                    @php
                                        $dayMapping = [
                                            'Monday' => 'Senin',
                                            'Tuesday' => 'Selasa', 
                                            'Wednesday' => 'Rabu',
                                            'Thursday' => 'Kamis',
                                            'Friday' => 'Jumat',
                                            'Saturday' => 'Sabtu'
                                        ];
                                        $today = $dayMapping[now()->format('l')] ?? '';
                                    @endphp
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                        <th class="text-center fw-bold" style="vertical-align: middle;">
                                            {{ $day }}
                                            @if($day === $today)
                                                <br><span class="badge bg-success btn-sm">Hari Ini</span>
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $timeSlots = [
                                        '07:00-07:45',
                                        '07:45-08:30', 
                                        '08:30-09:15',
                                        '09:15-10:00',
                                        '10:15-11:00',
                                        '11:00-11:45',
                                        '11:45-12:30',
                                        '13:00-13:45',
                                        '13:45-14:30',
                                        '14:30-15:15'
                                    ];
                                    
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                @endphp
                                
                                @foreach($timeSlots as $index => $timeSlot)
                                @php
                                    $isCurrentTime = false;
                                    $currentHour = now()->format('H:i');
                                    $timeRange = explode('-', $timeSlot);
                                    if ($currentHour >= $timeRange[0] && $currentHour <= $timeRange[1]) {
                                        $isCurrentTime = true;
                                    }
                                @endphp
                                <tr class="{{ $isCurrentTime ? 'table-warning' : '' }}">
                                    <td class="text-center fw-bold {{ $isCurrentTime ? 'bg-warning' : 'bg-light' }}" style="vertical-align: middle;">
                                        <div class="fw-bold">{{ $timeSlot }}</div>
                                        <small class="text-muted">45 menit</small>
                                    </td>
                                    @foreach($days as $dayIndex => $day)
                                        @php
                                            // Parse time slot
                                            $timeRange = explode('-', $timeSlot);
                                            $slotStart = \Carbon\Carbon::createFromFormat('H:i', $timeRange[0]);
                                            $slotEnd = \Carbon\Carbon::createFromFormat('H:i', $timeRange[1]);
                                            
                                            // Find schedule that overlaps with this time slot
                                            $schedule = $weeklySchedules->where('day', $day)
                                                                       ->filter(function($item) use ($slotStart, $slotEnd) {
                                                                           try {
                                                                               $scheduleStart = \Carbon\Carbon::createFromFormat('H:i:s', $item->start_time);
                                                                               $scheduleEnd = \Carbon\Carbon::createFromFormat('H:i:s', $item->end_time);
                                                                               
                                                                               // Check if schedule time overlaps with slot time
                                                                               return $scheduleStart->lt($slotEnd) && $scheduleEnd->gt($slotStart);
                                                                           } catch (\Exception $e) {
                                                                               return false;
                                                                           }
                                                                       })
                                                                       ->first();
                                            
                                            $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
                                            $bgColor = $colors[$dayIndex % count($colors)];
                                        @endphp
                                        <td class="text-center" style="vertical-align: middle; min-height: 80px;">
                                            @if($schedule)
                                                <div class="p-2 rounded bg-{{ $bgColor }} text-white shadow-sm" style="min-height: 70px; display: flex; flex-direction: column; justify-content: center;">
                                                    <div class="fw-bold mb-1" style="font-size: 0.9rem;">
                                                        {{ $schedule->subject->name }}
                                                    </div>
                                                    <div class="mb-1" style="font-size: 0.8rem; opacity: 0.9;">
                                                        <i class="fas fa-user me-1"></i>{{ $schedule->teacher->name }}
                                                    </div>
                                                    <div style="font-size: 0.75rem; opacity: 0.8;">
                                                        <i class="fas fa-door-open me-1"></i>{{ $schedule->room ?? 'Online' }}
                                                    </div>
                                                    <div class="mt-1" style="font-size: 0.7rem; opacity: 0.7;">
                                                        {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-muted d-flex align-items-center justify-content-center" style="min-height: 70px; border: 2px dashed #dee2e6; border-radius: 8px; background-color: #f8f9fa;">
                                                    <small>Kosong</small>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <h5>Jadwal Belum Tersedia</h5>
                        <p>Jadwal pelajaran untuk kelas Anda belum dibuat.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule Detail -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-day me-2"></i>Jadwal Hari Ini - {{ now()->format('l, d F Y') }}
                </h5>
            </div>
            <div class="card-body">
                @if(isset($todaySchedules) && $todaySchedules->count() > 0)
                    <div class="row">
                        @foreach($todaySchedules as $schedule)
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $schedule->subject->name }}</h6>
                                        @php
                                            $now = now();
                                            try {
                                                $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time);
                                                $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time);
                                                $isActive = $now->between($startTime, $endTime);
                                                $isNext = $now->lt($startTime) && $now->diffInMinutes($startTime) <= 30;
                                            } catch (\Exception $e) {
                                                $startTime = $endTime = $now;
                                                $isActive = $isNext = false;
                                            }
                                        @endphp
                                        
                                        @if($isActive)
                                            <span class="badge bg-success">Sedang Berlangsung</span>
                                        @elseif($isNext)
                                            <span class="badge bg-warning">Selanjutnya</span>
                                        @else
                                            <span class="badge bg-secondary">
                                                @php
                                                    try {
                                                        echo \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i');
                                                    } catch (\Exception $e) {
                                                        echo $schedule->start_time . ' - ' . $schedule->end_time;
                                                    }
                                                @endphp
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            @php
                                                try {
                                                    echo \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i');
                                                } catch (\Exception $e) {
                                                    echo $schedule->start_time . ' - ' . $schedule->end_time;
                                                }
                                            @endphp
                                        </small>
                                    </div>
                                    
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-user me-1"></i>{{ $schedule->teacher->name }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-door-open me-1"></i>{{ $schedule->room ?? 'Ruang TBA' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <h5>Tidak Ada Jadwal Hari Ini</h5>
                        <p>Anda tidak memiliki pelajaran pada hari ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Subject List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-book me-2"></i>Daftar Mata Pelajaran
                </h5>
            </div>
            <div class="card-body">
                @if(isset($subjects) && $subjects->count() > 0)
                    <div class="row">
                        @foreach($subjects as $subject)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    <div class="mb-2">
                                        <i class="fas fa-book-open fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title">{{ $subject->name }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">{{ $subject->code }}</small><br>
                                        <small class="text-muted">{{ $subject->credit_hours }} SKS</small>
                                    </p>
                                    <span class="badge bg-outline-primary">{{ $subject->category }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <h5>Belum Ada Mata Pelajaran</h5>
                        <p>Mata pelajaran untuk kelas Anda belum ditentukan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
/* Simple and Clean Schedule Styles */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table td, .table th {
    border: 1px solid #dee2e6;
    padding: 12px;
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table tbody td {
    background: #fff;
}

.table-warning td {
    background: rgba(255, 193, 7, 0.1) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffeb3b 100%) !important;
    color: #000 !important;
    font-weight: bold;
}

/* Schedule item hover effects */
.table tbody td > div.p-2:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table th, .table td {
        padding: 8px 4px;
        font-size: 0.85rem;
    }
    
    .table tbody td > div.p-2 {
        padding: 8px !important;
        min-height: 60px !important;
    }
}

/* Color adjustments for better contrast */
.bg-primary { background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%) !important; }
.bg-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%) !important; }
.bg-info { background: linear-gradient(135deg, #0dcaf0 0%, #6f42c1 100%) !important; }
.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important; color: #000 !important; }
.bg-danger { background: linear-gradient(135deg, #dc3545 0%, #e91e63 100%) !important; }
.bg-secondary { background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%) !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple current time highlighting
    function highlightCurrentTimeSlot() {
        const now = new Date();
        const currentTime = now.getHours() * 100 + now.getMinutes();
        
        // Remove existing highlights
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.classList.remove('table-warning');
        });
        
        // Find and highlight current time slot
        document.querySelectorAll('.table tbody tr').forEach(row => {
            const timeSlotCell = row.querySelector('td:first-child');
            if (timeSlotCell) {
                const timeSlotText = timeSlotCell.querySelector('.fw-bold');
                if (timeSlotText) {
                    const timeSlot = timeSlotText.textContent.trim();
                    if (timeSlot.includes('-')) {
                        const [startTime, endTime] = timeSlot.split('-');
                        const startTimeNum = parseInt(startTime.replace(':', ''));
                        const endTimeNum = parseInt(endTime.replace(':', ''));
                        
                        if (currentTime >= startTimeNum && currentTime <= endTimeNum) {
                            row.classList.add('table-warning');
                        }
                    }
                }
            }
        });
    }
    
    // Update highlights every minute
    highlightCurrentTimeSlot();
    setInterval(highlightCurrentTimeSlot, 60000);
    
    // Auto-refresh every 5 minutes
    setInterval(function() {
        window.location.reload();
    }, 300000);
    
    // Add smooth hover effects
    document.querySelectorAll('.table tbody td > div.p-2').forEach(item => {
        item.style.transition = 'all 0.3s ease';
    });
});
</script>
@endpush
