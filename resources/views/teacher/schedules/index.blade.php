@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-calendar-alt me-2"></i>Jadwal Mengajar</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Teacher Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=60&background=2563eb&color=ffffff" 
                             class="rounded-circle" width="60" height="60" alt="Teacher Photo">
                    </div>
                    <div class="col">
                        <h5 class="mb-1 fw-bold">{{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-0">Jadwal Mengajar - SMAN 1 Nagreg</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Schedule -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0"><i class="fas fa-calendar-week me-2"></i>Jadwal Mingguan</h5>
            </div>
            <div class="card-body">
                @if($schedulesByDay->count() > 0)
                    <div class="table-responsive">
                        @foreach($days as $day)
                            @php
                                $dayIndonesian = [
                                    'Monday' => 'Senin',
                                    'Tuesday' => 'Selasa', 
                                    'Wednesday' => 'Rabu',
                                    'Thursday' => 'Kamis',
                                    'Friday' => 'Jumat',
                                    'Saturday' => 'Sabtu'
                                ];
                                $daySchedules = $schedulesByDay->get($day, collect());
                            @endphp
                            
                            <div class="mb-4">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-calendar-day me-2"></i>{{ $dayIndonesian[$day] }}
                                </h6>
                                
                                @if($daySchedules->count() > 0)
                                    <div class="row g-3">
                                        @foreach($daySchedules as $schedule)
                                            <div class="col-lg-6 col-md-12">
                                                <div class="card border-0 bg-light h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="card-title mb-0 fw-bold">{{ $schedule->subject->name }}</h6>
                                                            <span class="badge bg-primary">{{ $schedule->classRoom->name }}</span>
                                                        </div>
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="fas fa-school me-1"></i>{{ $schedule->classRoom->name }}
                                                            </small>
                                                            <a href="{{ route('teacher.schedules.show', $schedule->id) }}" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye me-1"></i>Detail
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <p class="text-muted mb-0">Tidak ada jadwal pada hari ini</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Jadwal Mengajar</h5>
                        <p class="text-muted">Hubungi administrator untuk mengatur jadwal mengajar Anda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
