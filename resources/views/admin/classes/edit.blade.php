@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2"></i>Edit Data Kelas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.classes.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Data Kelas: {{ $class->name }}</h5>
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

        <form action="{{ route('admin.classes.update', $class) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Dasar</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $class->name) }}" 
                               placeholder="Contoh: X-1, XI-2, XII-3" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Kelas</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $class->code) }}" 
                               placeholder="Contoh: X1, XI2, XII3">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="grade" class="form-label">Tingkat <span class="text-danger">*</span></label>
                        <select class="form-select @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                            <option value="">Pilih Tingkat</option>
                            <option value="X" {{ old('grade', $class->grade) === 'X' ? 'selected' : '' }}>Kelas X</option>
                            <option value="XI" {{ old('grade', $class->grade) === 'XI' ? 'selected' : '' }}>Kelas XI</option>
                            <option value="XII" {{ old('grade', $class->grade) === 'XII' ? 'selected' : '' }}>Kelas XII</option>
                        </select>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Kapasitas Siswa <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                               id="capacity" name="capacity" value="{{ old('capacity', $class->capacity) }}" 
                               min="1" max="50" required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="academic_year" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('academic_year') is-invalid @enderror" 
                               id="academic_year" name="academic_year" value="{{ old('academic_year', $class->academic_year) }}" 
                               placeholder="Contoh: 2025/2026" required>
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="room" class="form-label">Ruang Kelas</label>
                        <input type="text" class="form-control @error('room') is-invalid @enderror" 
                               id="room" name="room" value="{{ old('room', $class->room) }}" 
                               placeholder="Contoh: Ruang 101, Lab IPA 1">
                        @error('room')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Teacher Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Guru</h6>
                </div>
                
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="homeroom_teacher_id" class="form-label">Wali Kelas</label>
                        <select class="form-select @error('homeroom_teacher_id') is-invalid @enderror" 
                                id="homeroom_teacher_id" name="homeroom_teacher_id">
                            <option value="">Pilih Wali Kelas (Opsional)</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" 
                                    {{ old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} - {{ $teacher->nip }}
                                </option>
                            @endforeach
                        </select>
                        @error('homeroom_teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Tambahan</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                            <option value="1" {{ old('is_active', $class->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $class->is_active) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Deskripsi atau keterangan tambahan untuk kelas ini">{{ old('description', $class->description) }}</textarea>
                        @error('description')
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
                            <i class="fas fa-save me-1"></i>Update Data
                        </button>
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
