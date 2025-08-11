@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-plus me-2"></i>Tambah Data Guru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Formulir Data Guru Baru</h5>
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

        <form action="{{ route('admin.teachers.store') }}" method="POST">
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
                        <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                               id="nip" name="nip" value="{{ old('nip') }}" required>
                        @error('nip')
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
                
                <div class="col-md-6">
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
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">No. HP</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Educational Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Pendidikan</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="education_level" class="form-label">Tingkat Pendidikan <span class="text-danger">*</span></label>
                        <select class="form-select @error('education_level') is-invalid @enderror" id="education_level" name="education_level" required>
                            <option value="">Pilih Tingkat Pendidikan</option>
                            <option value="S1" {{ old('education_level') === 'S1' ? 'selected' : '' }}>S1 (Sarjana)</option>
                            <option value="S2" {{ old('education_level') === 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                            <option value="S3" {{ old('education_level') === 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                        </select>
                        @error('education_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="major" class="form-label">Jurusan/Bidang Studi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('major') is-invalid @enderror" 
                               id="major" name="major" value="{{ old('major') }}" 
                               placeholder="Contoh: Pendidikan Matematika" required>
                        @error('major')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="hire_date" class="form-label">Tanggal Mulai Mengajar <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                               id="hire_date" name="hire_date" value="{{ old('hire_date') }}" required>
                        @error('hire_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subjects" class="form-label">Mata Pelajaran yang Diampu <span class="text-danger">*</span></label>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start @error('subjects') is-invalid @enderror" 
                                    type="button" id="subjectsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="selectedSubjectsText">Pilih Mata Pelajaran</span>
                            </button>
                            <div class="dropdown-menu w-100 p-2" aria-labelledby="subjectsDropdown" style="max-height: 300px; overflow-y: auto;">
                                @foreach($subjects as $subject)
                                    <div class="form-check">
                                        <input class="form-check-input subject-checkbox" type="checkbox" 
                                               value="{{ $subject->id }}" id="subject_{{ $subject->id }}" 
                                               name="subjects[]"
                                               {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="subject_{{ $subject->id }}">
                                            {{ $subject->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <small class="form-text text-muted">Pilih satu atau lebih mata pelajaran yang akan diampu</small>
                        @error('subjects')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
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
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
    const selectedSubjectsText = document.getElementById('selectedSubjectsText');
    const dropdownButton = document.getElementById('subjectsDropdown');

    function updateSelectedText() {
        const checkedBoxes = document.querySelectorAll('.subject-checkbox:checked');
        if (checkedBoxes.length === 0) {
            selectedSubjectsText.textContent = 'Pilih Mata Pelajaran';
            dropdownButton.classList.add('text-muted');
        } else if (checkedBoxes.length === 1) {
            selectedSubjectsText.textContent = checkedBoxes[0].nextElementSibling.textContent;
            dropdownButton.classList.remove('text-muted');
        } else {
            selectedSubjectsText.textContent = `${checkedBoxes.length} mata pelajaran dipilih`;
            dropdownButton.classList.remove('text-muted');
        }
    }

    subjectCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedText);
    });

    // Initialize on page load
    updateSelectedText();

    // Prevent dropdown from closing when clicking on checkboxes
    document.querySelector('.dropdown-menu').addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>
@endsection
