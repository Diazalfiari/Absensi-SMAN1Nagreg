@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2"></i>Edit Mata Pelajaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-eye me-1"></i>Lihat Detail
            </a>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Form Edit Data Mata Pelajaran</h5>
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

        <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Informasi Dasar</h6>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $subject->name) }}" 
                               placeholder="Contoh: Matematika, Bahasa Indonesia" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $subject->code) }}" 
                               placeholder="Contoh: MTK, BIN, FIS" maxlength="10" required>
                        <small class="form-text text-muted">Maksimal 10 karakter</small>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Wajib" {{ (old('category', $subject->category) === 'Wajib') ? 'selected' : '' }}>Mata Pelajaran Wajib</option>
                            <option value="Peminatan" {{ (old('category', $subject->category) === 'Peminatan') ? 'selected' : '' }}>Mata Pelajaran Peminatan</option>
                            <option value="Muatan Lokal" {{ (old('category', $subject->category) === 'Muatan Lokal') ? 'selected' : '' }}>Muatan Lokal</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="credit_hours" class="form-label">Jam Pelajaran per Minggu <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" 
                               id="credit_hours" name="credit_hours" value="{{ old('credit_hours', $subject->credit_hours) }}" 
                               min="1" max="10" required>
                        <small class="form-text text-muted">Jumlah jam pelajaran dalam 1 minggu</small>
                        @error('credit_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Deskripsi atau keterangan mata pelajaran">{{ old('description', $subject->description) }}</textarea>
                        @error('description')
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
                        <label class="form-label">Dibuat</label>
                        <input type="text" class="form-control" 
                               value="{{ $subject->created_at->format('d/m/Y H:i') }}" readonly>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Terakhir Diubah</label>
                        <input type="text" class="form-control" 
                               value="{{ $subject->updated_at->format('d/m/Y H:i') }}" readonly>
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
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-outline-info">
                            <i class="fas fa-eye me-1"></i>Lihat Detail
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
    // Auto-generate code based on name if code is empty
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const originalCode = codeInput.value;
    
    nameInput.addEventListener('input', function() {
        // Only suggest if the current code is empty or was auto-generated
        if (!codeInput.value || codeInput.dataset.autoGenerated === 'true') {
            const name = this.value;
            let code = '';
            
            // Generate code from first letters of each word
            const words = name.split(' ');
            words.forEach(word => {
                if (word.length > 0) {
                    code += word.charAt(0).toUpperCase();
                }
            });
            
            // Limit to 10 characters
            code = code.substring(0, 10);
            codeInput.placeholder = 'Saran: ' + code;
        }
    });
    
    // Track if code was manually changed
    codeInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
});
</script>
@endpush
