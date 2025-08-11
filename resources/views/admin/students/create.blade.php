@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-plus me-2"></i>Tambah Siswa Baru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Form Data Siswa</h5>
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

        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf
            
            <!-- Personal Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Pribadi</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                               id="nisn" name="nisn" value="{{ old('nisn') }}" required>
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nis') is-invalid @enderror" 
                               id="nis" name="nis" value="{{ old('nis') }}" required>
                        @error('nis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                               id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                               id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required>
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Kontak</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">No. HP Siswa</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="parent_phone" class="form-label">No. HP Orang Tua</label>
                        <input type="text" class="form-control @error('parent_phone') is-invalid @enderror" 
                               id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}">
                        @error('parent_phone')
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
                        <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                            <option value="">Pilih Kelas</option>
                            @forelse($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} - {{ $class->grade }}
                                    @if($class->room)
                                        ({{ $class->room }})
                                    @endif
                                </option>
                            @empty
                                <option value="" disabled>Belum ada kelas tersedia</option>
                            @endforelse
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="entry_date" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('entry_date') is-invalid @enderror" 
                               id="entry_date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                        @error('entry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Akun</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Data
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
