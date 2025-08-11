@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-clipboard-check me-2"></i>Data Absensi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Input Absensi
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download me-1"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total Absensi Hari Ini</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-clipboard-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Hadir</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Izin/Sakit</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Alpa</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select class="form-select">
                    <option value="">Semua Kelas</option>
                    <option value="X-IPA-1">X IPA 1</option>
                    <option value="X-IPA-2">X IPA 2</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Mata Pelajaran</label>
                <select class="form-select">
                    <option value="">Semua Mata Pelajaran</option>
                    <option value="matematika">Matematika</option>
                    <option value="bahasa-indonesia">Bahasa Indonesia</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Data Absensi - {{ date('d F Y') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Data absensi akan dimuat setelah setup database</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
