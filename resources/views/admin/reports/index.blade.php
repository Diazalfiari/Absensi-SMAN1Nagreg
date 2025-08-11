@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-chart-bar me-2"></i>Laporan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-download me-1"></i>Export PDF
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </button>
        </div>
    </div>
</div>

<!-- Report Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Filter Laporan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Jenis Laporan</label>
                <select class="form-select">
                    <option value="daily">Harian</option>
                    <option value="weekly">Mingguan</option>
                    <option value="monthly">Bulanan</option>
                    <option value="semester">Semester</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" value="{{ date('Y-m-01') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select class="form-select">
                    <option value="">Semua Kelas</option>
                    <option value="X">Kelas X</option>
                    <option value="XI">Kelas XI</option>
                    <option value="XII">Kelas XII</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>Generate Laporan
                </button>
                <button type="button" class="btn btn-outline-secondary">
                    <i class="fas fa-redo me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total Kehadiran</h5>
                        <h3 class="mt-1 mb-0">0%</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-percentage fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Siswa Hadir</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-user-check fa-2x"></i>
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
                        <i class="fas fa-user-clock fa-2x"></i>
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
                        <i class="fas fa-user-times fa-2x"></i>
                    </div>
                </ada>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Grafik Kehadiran Bulanan</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Grafik akan ditampilkan setelah data tersedia</h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 5 Kelas Terbaik</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Data ranking akan ditampilkan</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Report Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Detail Laporan Kehadiran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Total Siswa</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpa</th>
                        <th>Persentase Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Laporan akan dimuat setelah setup database</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
