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
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-week me-2"></i>Jadwal Mingguan
                </h5>
            </div>
            <div class="card-body">
                @if(isset($weeklySchedules) && $weeklySchedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 100px;">Jam</th>
                                    <th class="text-center">Senin</th>
                                    <th class="text-center">Selasa</th>
                                    <th class="text-center">Rabu</th>
                                    <th class="text-center">Kamis</th>
                                    <th class="text-center">Jumat</th>
                                    <th class="text-center">Sabtu</th>
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
                                    
                                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                                    $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                @endphp
                                
                                @foreach($timeSlots as $timeSlot)
                                <tr>
                                    <td class="text-center fw-bold bg-light">{{ $timeSlot }}</td>
                                    @foreach($days as $day)
                                        @php
                                            $schedule = $weeklySchedules->where('day', $day)
                                                                       ->where('start_time', '<=', explode('-', $timeSlot)[0] . ':00')
                                                                       ->where('end_time', '>', explode('-', $timeSlot)[0] . ':00')
                                                                       ->first();
                                        @endphp
                                        <td class="text-center">
                                            @if($schedule)
                                                <div class="p-2 rounded bg-primary text-white">
                                                    <strong>{{ $schedule->subject->name }}</strong><br>
                                                    <small>{{ $schedule->teacher->name }}</small><br>
                                                    <small><i class="fas fa-door-open"></i> {{ $schedule->room ?? 'TBA' }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
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
                                            $startTime = \Carbon\Carbon::parse($schedule->start_time);
                                            $endTime = \Carbon\Carbon::parse($schedule->end_time);
                                            $isActive = $now->between($startTime, $endTime);
                                            $isNext = $now->lt($startTime) && $now->diffInMinutes($startTime) <= 30;
                                        @endphp
                                        
                                        @if($isActive)
                                            <span class="badge bg-success">Sedang Berlangsung</span>
                                        @elseif($isNext)
                                            <span class="badge bg-warning">Selanjutnya</span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Highlight current time slot
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
                const timeSlot = timeSlotCell.textContent.trim();
                const [startTime, endTime] = timeSlot.split('-');
                
                const startTimeNum = parseInt(startTime.replace(':', ''));
                const endTimeNum = parseInt(endTime.replace(':', ''));
                
                if (currentTime >= startTimeNum && currentTime <= endTimeNum) {
                    row.classList.add('table-warning');
                }
            }
        });
    }
    
    // Highlight current time slot initially and every minute
    highlightCurrentTimeSlot();
    setInterval(highlightCurrentTimeSlot, 60000);
    
    // Auto-refresh page every 5 minutes to update "Sedang Berlangsung" status
    setInterval(function() {
        location.reload();
    }, 300000); // 5 minutes
});
</script>
@endpush
