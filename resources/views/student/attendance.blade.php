@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-camera me-2"></i>Absensi Siswa</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('student.attendance.history') }}" class="btn btn-outline-primary">
                <i class="fas fa-history me-1"></i>Riwayat Absensi
            </a>
        </div>
        <div class="btn-group me-2">
            <span class="badge bg-success fs-6">
                <i class="fas fa-calendar-day me-1"></i>{{ now()->format('d F Y') }}
            </span>
        </div>
    </div>
</div>

<!-- Current Time & Location -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="mb-2">
                    <i class="fas fa-clock me-2"></i>
                    <span id="current-time-detailed">{{ now()->format('H:i:s') }}</span>
                </h4>
                <p class="text-muted mb-0">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <span id="current-location">Mendeteksi lokasi...</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedules -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-check me-2"></i>Jadwal Pelajaran Hari Ini
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
                                        <span class="badge bg-primary">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-user me-1"></i>{{ $schedule->teacher->name }}
                                    </p>
                                    <p class="text-muted mb-3">
                                        <i class="fas fa-door-open me-1"></i>{{ $schedule->room ?? 'Ruang TBA' }}
                                    </p>
                                    
                                    @php
                                        $now = now();
                                        $startTime = \Carbon\Carbon::parse($schedule->start_time);
                                        $endTime = \Carbon\Carbon::parse($schedule->end_time);
                                        $canAttend = $now->between($startTime->subMinutes(15), $endTime);
                                        $isActive = $now->between($startTime, $endTime);
                                    @endphp
                                    
                                    @if($canAttend)
                                        <button class="btn btn-success w-100 btn-attendance" 
                                                data-schedule-id="{{ $schedule->id }}"
                                                data-subject="{{ $schedule->subject->name }}">
                                            <i class="fas fa-camera me-1"></i>
                                            {{ $isActive ? 'Absen Sekarang' : 'Absen Masuk' }}
                                        </button>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-clock me-1"></i>Belum Waktunya
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <h5>Tidak Ada Jadwal Hari Ini</h5>
                        <p>Selamat menikmati hari libur!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">
                    <i class="fas fa-camera me-2"></i>Absensi - <span id="modal-subject"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="camera-preview position-relative">
                            <video id="camera-feed" autoplay playsinline class="w-100" style="max-height: 400px;"></video>
                            <canvas id="photo-canvas" style="display: none;"></canvas>
                            
                            <!-- Camera overlay guide -->
                            <div class="position-absolute top-50 start-50 translate-middle" 
                                 style="border: 3px solid #fff; border-radius: 50%; width: 200px; height: 200px; 
                                        border-style: dashed; opacity: 0.7; pointer-events: none;">
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button id="capture-btn" class="btn btn-primary btn-lg me-2">
                                <i class="fas fa-camera me-1"></i>Ambil Foto
                            </button>
                            <button id="retake-btn" class="btn btn-warning btn-lg me-2" style="display: none;">
                                <i class="fas fa-redo me-1"></i>Ambil Ulang
                            </button>
                            <button id="confirm-btn" class="btn btn-success btn-lg" style="display: none;">
                                <i class="fas fa-check me-1"></i>Konfirmasi
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Detail Absensi</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Status Kehadiran</label>
                                    <select class="form-select" id="attendance-status">
                                        <option value="hadir" selected>Hadir</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="izin">Izin</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Keterangan (Opsional)</label>
                                    <textarea class="form-control" id="attendance-notes" rows="3" 
                                              placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="attendance-location" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Waktu</label>
                                    <input type="text" class="form-control" id="attendance-time" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Riwayat Absensi Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recentAttendances) && $recentAttendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d M Y') }}</td>
                                    <td>{{ $attendance->schedule->subject->name }}</td>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</td>
                                    <td>
                                        <span class="attendance-status status-{{ $attendance->status }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <h5>Belum Ada Riwayat Absensi</h5>
                        <p>Mulai absensi untuk melihat riwayat kehadiran Anda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentStream = null;
let currentScheduleId = null;
let photoBlob = null;

document.addEventListener('DOMContentLoaded', function() {
    // Update detailed time every second
    function updateDetailedTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        
        const timeElement = document.getElementById('current-time-detailed');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
        
        // Update attendance time in modal
        const attendanceTimeElement = document.getElementById('attendance-time');
        if (attendanceTimeElement) {
            attendanceTimeElement.value = now.toLocaleString('id-ID');
        }
    }
    
    setInterval(updateDetailedTime, 1000);
    updateDetailedTime();
    
    // Get current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Use reverse geocoding to get address (you can use any geocoding service)
                document.getElementById('current-location').textContent = 
                    `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                    
                // Update attendance location in modal
                const attendanceLocationElement = document.getElementById('attendance-location');
                if (attendanceLocationElement) {
                    attendanceLocationElement.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                }
            },
            function(error) {
                document.getElementById('current-location').textContent = 'Lokasi tidak tersedia';
            }
        );
    }
    
    // Handle attendance button clicks
    document.querySelectorAll('.btn-attendance').forEach(button => {
        button.addEventListener('click', function() {
            currentScheduleId = this.dataset.scheduleId;
            document.getElementById('modal-subject').textContent = this.dataset.subject;
            
            const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
            modal.show();
            
            // Start camera when modal is shown
            startCamera();
        });
    });
    
    // Camera functions
    async function startCamera() {
        try {
            currentStream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                } 
            });
            
            const video = document.getElementById('camera-feed');
            video.srcObject = currentStream;
        } catch (error) {
            console.error('Error accessing camera:', error);
            alert('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin kamera.');
        }
    }
    
    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
        }
    }
    
    // Capture photo
    document.getElementById('capture-btn').addEventListener('click', function() {
        const video = document.getElementById('camera-feed');
        const canvas = document.getElementById('photo-canvas');
        const ctx = canvas.getContext('2d');
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        ctx.drawImage(video, 0, 0);
        
        // Convert to blob
        canvas.toBlob(function(blob) {
            photoBlob = blob;
            
            // Show photo in video element
            const photoUrl = URL.createObjectURL(blob);
            video.srcObject = null;
            video.src = photoUrl;
            
            // Show/hide buttons
            document.getElementById('capture-btn').style.display = 'none';
            document.getElementById('retake-btn').style.display = 'inline-block';
            document.getElementById('confirm-btn').style.display = 'inline-block';
            
            stopCamera();
        }, 'image/jpeg', 0.8);
    });
    
    // Retake photo
    document.getElementById('retake-btn').addEventListener('click', function() {
        document.getElementById('capture-btn').style.display = 'inline-block';
        document.getElementById('retake-btn').style.display = 'none';
        document.getElementById('confirm-btn').style.display = 'none';
        
        photoBlob = null;
        startCamera();
    });
    
    // Confirm attendance
    document.getElementById('confirm-btn').addEventListener('click', function() {
        if (!photoBlob || !currentScheduleId) {
            alert('Data tidak lengkap!');
            return;
        }
        
        // Convert photo blob to base64
        const reader = new FileReader();
        reader.onloadend = function() {
            const base64Photo = reader.result;
            
            // Get current location if available
            navigator.geolocation.getCurrentPosition(function(position) {
                submitAttendance(base64Photo, position.coords.latitude, position.coords.longitude);
            }, function(error) {
                // Submit without location if geolocation fails
                submitAttendance(base64Photo, null, null);
            });
        };
        reader.readAsDataURL(photoBlob);
    });
    
    function submitAttendance(photo, latitude, longitude) {
        const submitBtn = document.getElementById('confirm-btn');
        
        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Menyimpan...';
        
        const attendanceData = {
            schedule_id: currentScheduleId,
            status: document.getElementById('attendance-status').value,
            notes: document.getElementById('attendance-notes').value,
            photo: photo,
            latitude: latitude,
            longitude: longitude,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
        
        // Submit attendance
        fetch('{{ route("student.attendance.submit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(attendanceData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Absensi berhasil disimpan!');
                bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();
                location.reload();
            } else {
                alert('Gagal menyimpan absensi: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan absensi.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Konfirmasi';
        });
    }
    
    // Clean up when modal is closed
    document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function() {
        stopCamera();
        
        // Reset modal state
        document.getElementById('capture-btn').style.display = 'inline-block';
        document.getElementById('retake-btn').style.display = 'none';
        document.getElementById('confirm-btn').style.display = 'none';
        
        const video = document.getElementById('camera-feed');
        video.srcObject = null;
        video.src = '';
        
        photoBlob = null;
        currentScheduleId = null;
    });
});
</script>
@endpush
