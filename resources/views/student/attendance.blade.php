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
                <!-- Debug info -->
                <div class="alert alert-info mb-3">
                    <small>
                        Debug: Total schedules today = {{ isset($todaySchedules) ? $todaySchedules->count() : 'undefined' }}<br>
                        Current time (WIB): {{ now()->format('Y-m-d H:i:s T') }}<br>
                        Student: {{ Auth::user()->student->name ?? 'N/A' }} (Class: {{ Auth::user()->student->class_room->name ?? 'N/A' }})
                    </small>
                </div>
                
                @if(isset($todaySchedules) && $todaySchedules->count() > 0)
                    <div class="row">
                        @foreach($todaySchedules as $schedule)
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $schedule->subject->name }}</h6>
                                        @php
                                            try {
                                                $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time);
                                                $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time);
                                                $timeDisplay = $startTime->format('H:i') . ' - ' . $endTime->format('H:i');
                                            } catch (\Exception $e) {
                                                $timeDisplay = $schedule->start_time . ' - ' . $schedule->end_time;
                                            }
                                        @endphp
                                        <span class="badge bg-primary">{{ $timeDisplay }}</span>
                                    </div>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-user me-1"></i>{{ $schedule->teacher->name }}
                                    </p>
                                    <p class="text-muted mb-3">
                                        <i class="fas fa-door-open me-1"></i>{{ $schedule->room ?? 'Ruang TBA' }}
                                    </p>
                                    
                                    @php
                                        $now = now();
                                        try {
                                            $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time);
                                            $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time);
                                            
                                            // Set today's date for proper comparison
                                            $startTime->setDateFrom($now);
                                            $endTime->setDateFrom($now);
                                            
                                            // Can attend 15 minutes before until end of class
                                            $canAttend = $now->between($startTime->copy()->subMinutes(15), $endTime);
                                            $isActive = $now->between($startTime, $endTime);
                                            $isPast = $now->gt($endTime);
                                            
                                            // FOR TESTING: Allow attendance anytime (remove this in production)
                                            $testingMode = true; // Set to false in production
                                            if ($testingMode) {
                                                $canAttend = true;
                                                $isPast = false;
                                            }
                                            
                                        } catch (\Exception $e) {
                                            $canAttend = false;
                                            $isActive = false;
                                            $isPast = true;
                                        }
                                    @endphp
                                    
                                    <!-- Debug schedule info -->
                                    <div class="alert alert-warning p-2 mb-2">
                                        <small>
                                            Schedule ID: {{ $schedule->id }}<br>
                                            Time: {{ $schedule->start_time }} - {{ $schedule->end_time }}<br>
                                            canAttend: {{ $canAttend ? 'true' : 'false' }}<br>
                                            isPast: {{ $isPast ? 'true' : 'false' }}<br>
                                            isActive: {{ $isActive ? 'true' : 'false' }}<br>
                                            testingMode: {{ $testingMode ? 'true' : 'false' }}
                                        </small>
                                    </div>
                                    
                                    @if($canAttend && !$isPast)
                                        <button class="btn btn-success w-100 btn-attendance" 
                                                data-schedule-id="{{ $schedule->id }}"
                                                data-subject="{{ $schedule->subject->name }}">
                                            <i class="fas fa-camera me-1"></i>
                                            {{ $isActive ? 'Absen Sekarang' : 'Absen Masuk' }}
                                        </button>
                                        <!-- Debug info -->
                                        <small class="text-muted d-block mt-1">
                                            Debug: canAttend=true, isPast=false, testMode=on
                                        </small>
                                    @elseif($isPast)
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-times me-1"></i>Waktu Habis
                                        </button>
                                        <small class="text-muted d-block mt-1">
                                            Debug: isPast=true
                                        </small>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-clock me-1"></i>Belum Waktunya
                                        </button>
                                        <small class="text-muted d-block mt-1">
                                            Debug: canAttend=false, testMode={{ $testingMode ?? 'false' }}
                                        </small>
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
                                    <td>
                                        {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                                        @if($attendance->check_in)
                                            <small class="text-muted d-block">{{ $attendance->check_in->format('T') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $attendance->getStatusColor() }}">
                                            {{ $attendance->getStatusLabel() }}
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
<style>
/* Attendance Status Styles */
.attendance-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-hadir {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-sakit {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-izin {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status-alpha {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Camera Modal Styles */
.camera-preview {
    background: #000;
    border-radius: 10px;
    overflow: hidden;
}

#camera-feed {
    border-radius: 10px;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .modal-lg {
        max-width: 95%;
    }
    
    .camera-preview {
        margin-bottom: 15px;
    }
}
</style>

<script>
console.log('=== SCRIPT LOADING START ===');
console.log('Window loaded:', document.readyState);
console.log('Bootstrap available:', typeof bootstrap !== 'undefined');

let currentStream = null;
let currentScheduleId = null;
let photoBlob = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PAGE LOADED - ATTENDANCE SYSTEM INITIALIZING ===');
    console.log('Current URL:', window.location.href);
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    console.log('DOM ready, starting initialization...');
    
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
    
    // Handle attendance status change
    document.getElementById('attendance-status').addEventListener('change', function() {
        const status = this.value;
        const cameraSection = document.querySelector('.camera-preview').parentElement;
        const confirmBtn = document.getElementById('confirm-btn');
        
        if (status === 'hadir') {
            // Show camera section and require photo
            cameraSection.style.display = 'block';
            confirmBtn.style.display = photoBlob ? 'inline-block' : 'none';
            document.getElementById('capture-btn').style.display = photoBlob ? 'none' : 'inline-block';
            document.getElementById('retake-btn').style.display = photoBlob ? 'inline-block' : 'none';
        } else {
            // Hide camera section and allow direct submission
            cameraSection.style.display = 'none';
            confirmBtn.style.display = 'inline-block';
            stopCamera();
        }
    });
    console.log('=== INITIALIZING ATTENDANCE BUTTONS ===');
    const attendanceButtons = document.querySelectorAll('.btn-attendance');
    console.log('Found attendance buttons:', attendanceButtons.length);
    console.log('Buttons list:', attendanceButtons);
    
    if (attendanceButtons.length === 0) {
        console.error('❌ NO ATTENDANCE BUTTONS FOUND!');
        console.warn('Possible reasons: 1) No schedules today, 2) All schedules past time, 3) Student not enrolled in any class');
        // No annoying alert - just log the info
    }
    
    // Handle attendance button clicks
    document.querySelectorAll('.btn-attendance').forEach(button => {
        console.log('=== REGISTERING EVENT LISTENER ===');
        console.log('Button found:', button);
        console.log('Button class:', button.className);
        console.log('Button dataset:', button.dataset);
        
        button.addEventListener('click', function(event) {
            console.log('=== ATTENDANCE BUTTON CLICKED ===');
            console.log('Event:', event);
            console.log('Button clicked:', this);
            console.log('Button dataset:', this.dataset);
            
            alert('🔥 BUTTON CLICKED! Check Console (F12) for details');
            
            currentScheduleId = this.dataset.scheduleId;
            console.log('Schedule ID set to:', currentScheduleId);
            
            document.getElementById('modal-subject').textContent = this.dataset.subject;
            
            const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
            modal.show();
            
            // Start camera when modal is shown
            setTimeout(() => {
                startCamera();
            }, 500);
        });
    });
    
    // Camera functions
    async function startCamera() {
        console.log('=== STARTING CAMERA ===');
        console.log('Navigator available:', typeof navigator !== 'undefined');
        console.log('MediaDevices available:', typeof navigator.mediaDevices !== 'undefined');
        console.log('GetUserMedia available:', navigator.mediaDevices && typeof navigator.mediaDevices.getUserMedia !== 'undefined');
        console.log('Current protocol:', window.location.protocol);
        console.log('Is HTTPS or localhost:', window.location.protocol === 'https:' || window.location.hostname === 'localhost');
        
        try {
            // Check if camera is supported
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Camera not supported in this browser or requires HTTPS');
            }
            
            // Stop any existing stream first
            if (currentStream) {
                stopCamera();
            }
            
            console.log('Requesting camera access...');
            currentStream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'user',
                    width: { ideal: 640, max: 1280 },
                    height: { ideal: 480, max: 720 }
                } 
            });
            
            console.log('Camera access granted, setting up video...');
            const video = document.getElementById('camera-feed');
            video.srcObject = currentStream;
            
            console.log('Camera started successfully');
            
            // Wait for video to load
            video.onloadedmetadata = function() {
                video.play();
            };
            
        } catch (error) {
            console.error('=== CAMERA ERROR ===');
            console.error('Error type:', error.name);
            console.error('Error message:', error.message);
            console.error('Full error:', error);
            
            let errorMessage = 'Tidak dapat mengakses kamera: ' + error.message;
            let allowFallback = false;
            
            if (error.name === 'NotAllowedError') {
                errorMessage += '\n\n📱 Solusi: Berikan izin kamera di browser';
            } else if (error.name === 'NotFoundError') {
                errorMessage += '\n\n📷 Solusi: Pastikan kamera terpasang';
                allowFallback = true;
            } else if (error.message.includes('HTTPS')) {
                errorMessage += '\n\n🔒 Solusi: Gunakan HTTPS atau localhost';
                allowFallback = true;
            } else {
                allowFallback = true;
            }
            
            if (allowFallback) {
                errorMessage += '\n\n⚡ Opsi: Lanjut absensi tanpa foto?';
                if (confirm(errorMessage)) {
                    console.log('User chose to continue without photo');
                    // Enable manual attendance without photo
                    document.getElementById('confirm-btn').disabled = false;
                    document.getElementById('confirm-btn').innerHTML = 'Absen Tanpa Foto';
                    document.getElementById('confirm-btn').style.display = 'inline-block';
                    
                    // Hide camera related elements
                    document.getElementById('camera-feed').style.display = 'none';
                    document.getElementById('camera-controls').style.display = 'none';
                    
                    return;
                }
            } else {
                alert(errorMessage);
            }
            
            // Close modal if user doesn't want to continue
            const modal = bootstrap.Modal.getInstance(document.getElementById('cameraModal'));
            if (modal) {
                modal.hide();
            }
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
        console.log('Capture button clicked');
        const video = document.getElementById('camera-feed');
        const canvas = document.getElementById('photo-canvas');
        const ctx = canvas.getContext('2d');
        
        if (video.videoWidth === 0 || video.videoHeight === 0) {
            alert('Camera belum ready. Tunggu sebentar dan coba lagi.');
            return;
        }
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        ctx.drawImage(video, 0, 0);
        console.log('Photo captured, canvas size:', canvas.width, 'x', canvas.height);
        
        // Convert to blob
        canvas.toBlob(function(blob) {
            if (!blob) {
                alert('Gagal mengambil foto. Coba lagi.');
                return;
            }
            
            photoBlob = blob;
            console.log('Photo blob created, size:', blob.size);
            
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
        console.log('Confirm button clicked');
        
        if (!currentScheduleId) {
            alert('Data jadwal tidak ditemukan!');
            return;
        }
        
        const status = document.getElementById('attendance-status').value;
        console.log('Status:', status);
        
        // For non-hadir status, we don't need photo
        if (status !== 'hadir') {
            console.log('Non-hadir status, submitting without photo');
            submitAttendance(null, null, null);
            return;
        }
        
        if (!photoBlob) {
            alert('Silakan ambil foto terlebih dahulu!');
            return;
        }
        
        // Convert photo blob to base64
        const reader = new FileReader();
        reader.onloadend = function() {
            const base64Photo = reader.result;
            console.log('Photo converted to base64, length:', base64Photo.length);
            
            // Get current location if available
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    console.log('Location obtained:', position.coords.latitude, position.coords.longitude);
                    submitAttendance(base64Photo, position.coords.latitude, position.coords.longitude);
                }, function(error) {
                    console.log('Location error:', error);
                    // Submit without location if geolocation fails
                    submitAttendance(base64Photo, null, null);
                });
            } else {
                console.log('Geolocation not available');
                submitAttendance(base64Photo, null, null);
            }
        };
        reader.readAsDataURL(photoBlob);
    });
    
    function submitAttendance(photo, latitude, longitude) {
        console.log('=== DEBUGGING ATTENDANCE SUBMISSION ===');
        console.log('Submitting attendance...');
        console.log('Schedule ID:', currentScheduleId);
        console.log('Photo type:', typeof photo);
        console.log('Photo:', photo);
        
        // Show debugging alert
        if (confirm('DEBUG MODE: Akan mengirim data absensi. Buka Console (F12) untuk melihat detail. Lanjutkan?')) {
            console.log('User confirmed submission');
        } else {
            console.log('User cancelled submission');
            return;
        }
        
        const submitBtn = document.getElementById('confirm-btn');
        
        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Menyimpan...';
        
        // Convert blob to base64 if needed
        if (photo && typeof photo === 'object' && photo instanceof Blob) {
            console.log('Converting blob to base64...');
            const reader = new FileReader();
            reader.onload = function() {
                const base64Photo = reader.result;
                console.log('Blob converted to base64, size:', base64Photo.length);
                submitAttendanceData(base64Photo, latitude, longitude);
            };
            reader.readAsDataURL(photo);
        } else {
            // Photo is already base64 or null
            submitAttendanceData(photo, latitude, longitude);
        }
    }
    
    function submitAttendanceData(photo, latitude, longitude) {
        console.log('=== PREPARING ATTENDANCE DATA ===');
        console.log('Photo size:', photo ? photo.length : 'No photo');
        
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
        
        console.log('Attendance data to send:');
        console.log('- Schedule ID:', attendanceData.schedule_id);
        console.log('- Status:', attendanceData.status);
        console.log('- Photo length:', photo ? photo.length : 'No photo');
        console.log('- Latitude:', latitude);
        console.log('- Longitude:', longitude);
        console.log('- CSRF Token:', attendanceData._token ? 'Present' : 'Missing');
        
        console.log('About to send request to:', '{{ route("student.attendance.submit") }}');
        
        // Submit attendance
        fetch('{{ route("student.attendance.submit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(attendanceData)
        })
        .then(response => {
            console.log('Response received:');
            console.log('- Status:', response.status);
            console.log('- Status Text:', response.statusText);
            console.log('- OK:', response.ok);
            console.log('- Headers:', Object.fromEntries(response.headers.entries()));
            
            if (!response.ok) {
                console.error('HTTP error detected!');
                // Let's also try to read the response text for more info
                return response.text().then(text => {
                    console.error('Error response body:', text);
                    throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                alert('✅ SUCCESS: Absensi berhasil disimpan!\n\nCheck Console untuk detail lengkap.');
                bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();
                location.reload();
            } else {
                const errorMsg = 'FAILED: Gagal menyimpan absensi: ' + (data.message || 'Unknown error');
                console.error(errorMsg);
                alert('❌ ' + errorMsg + '\n\nCheck Console untuk detail lengkap.');
            }
        })
        .catch(error => {
            console.error('=== FETCH ERROR ===');
            console.error('Fetch error:', error);
            console.error('Error name:', error.name);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            
            const errorMsg = 'NETWORK/SERVER ERROR: Terjadi kesalahan saat menyimpan absensi: ' + error.message;
            alert('❌ ' + errorMsg + '\n\nCheck Console (F12) untuk detail lengkap.\n\nKemungkinan:\n- Koneksi internet\n- Server down\n- Route tidak ditemukan');
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
    
    // Add manual testing function to window for debugging
    window.testAttendanceButtons = function() {
        console.log('=== MANUAL BUTTON TEST ===');
        const buttons = document.querySelectorAll('.btn-attendance');
        console.log('Found buttons:', buttons.length);
        
        buttons.forEach((btn, index) => {
            console.log(`Button ${index}:`, btn);
            console.log(`- Classes:`, btn.className);
            console.log(`- Dataset:`, btn.dataset);
            console.log(`- Disabled:`, btn.disabled);
            console.log(`- Display:`, getComputedStyle(btn).display);
            console.log(`- Visibility:`, getComputedStyle(btn).visibility);
        });
        
        if (buttons.length > 0) {
            console.log('Attempting to click first button...');
            buttons[0].click();
        }
        
        return buttons;
    };
    
    console.log('=== DEBUGGING READY ===');
    console.log('Type testAttendanceButtons() in console to test manually');
});
</script>
@endpush
