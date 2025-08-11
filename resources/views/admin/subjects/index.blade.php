@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-book me-2"></i>Mata Pelajaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Tambah Mata Pelajaran
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
                        <h5 class="card-title text-muted mb-0">Total Mata Pelajaran</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-book fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Mata Pelajaran Wajib</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-bookmark fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Mata Pelajaran Pilihan</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-star fa-2x"></i>
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
                        <h5 class="card-title text-muted mb-0">Total Guru</h5>
                        <h3 class="mt-1 mb-0">0</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subjects Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Daftar Mata Pelajaran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Jenis</th>
                        <th>SKS</th>
                        <th>Guru Pengampu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Data mata pelajaran akan dimuat setelah setup database</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
