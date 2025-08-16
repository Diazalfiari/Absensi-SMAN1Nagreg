@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus me-2"></i>Tambah Jadwal Pelajaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Form Tambah Jadwal</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf
            
            <!-- Basic Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Dasar</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    [{{ $subject->code }}] {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Guru <span class="text-danger">*</span></label>
                        <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id" required>
                            <option value="">Pilih Guru</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="day" class="form-label">Hari <span class="text-danger">*</span></label>
                        <select class="form-select @error('day') is-invalid @enderror" id="day" name="day" required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin" {{ old('day') === 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ old('day') === 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ old('day') === 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ old('day') === 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ old('day') === 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ old('day') === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        </select>
                        @error('day')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                               id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                               id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="room" class="form-label">Ruangan</label>
                        <input type="text" class="form-control @error('room') is-invalid @enderror" 
                               id="room" name="room" value="{{ old('room') }}" 
                               placeholder="Contoh: Lab Komputer, Ruang 201">
                        <small class="form-text text-muted">Opsional - Kosongkan jika menggunakan ruang kelas biasa</small>
                        @error('room')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Academic Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Akademik</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="academic_year" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" required>
                            <option value="">Pilih Tahun Ajaran</option>
                            <option value="2023/2024" {{ old('academic_year') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                            <option value="2024/2025" {{ old('academic_year') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                            <option value="2025/2026" {{ old('academic_year') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                        </select>
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                            <option value="">Pilih Semester</option>
                            <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                            <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                        </select>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Jadwal
                        </button>
                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto calculate end time based on start time (45 minutes duration)
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    startTimeInput.addEventListener('change', function() {
        if (this.value && !endTimeInput.value) {
            const startTime = new Date('2000-01-01 ' + this.value);
            const endTime = new Date(startTime.getTime() + 45 * 60000); // Add 45 minutes
            
            const hours = endTime.getHours().toString().padStart(2, '0');
            const minutes = endTime.getMinutes().toString().padStart(2, '0');
            
            endTimeInput.value = hours + ':' + minutes;
        }
    });
    
    // Validate end time is after start time
    endTimeInput.addEventListener('change', function() {
        if (startTimeInput.value && this.value) {
            const startTime = new Date('2000-01-01 ' + startTimeInput.value);
            const endTime = new Date('2000-01-01 ' + this.value);
            
            if (endTime <= startTime) {
                alert('Jam selesai harus lebih besar dari jam mulai');
                this.value = '';
            }
        }
    });
});
</script>
@endpush
