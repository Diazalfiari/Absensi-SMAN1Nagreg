@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-calendar-alt me-2"></i>Jadwal Pelajaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Tambah Jadwal
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download me-1"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">Pilih Kelas</option>
                    <option value="X-IPA-1">X IPA 1</option>
                    <option value="X-IPA-2">X IPA 2</option>
                    <option value="XI-IPA-1">XI IPA 1</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">Pilih Hari</option>
                    <option value="senin">Senin</option>
                    <option value="selasa">Selasa</option>
                    <option value="rabu">Rabu</option>
                    <option value="kamis">Kamis</option>
                    <option value="jumat">Jumat</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">Pilih Mata Pelajaran</option>
                    <option value="matematika">Matematika</option>
                    <option value="bahasa-indonesia">Bahasa Indonesia</option>
                </select>
            </div>
            <div class="col-md-3">
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

<!-- Schedule Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Jadwal Pelajaran Minggu Ini</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Jam</th>
                        <th>Senin</th>
                        <th>Selasa</th>
                        <th>Rabu</th>
                        <th>Kamis</th>
                        <th>Jumat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">07:00 - 07:45</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">07:45 - 08:30</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">08:30 - 09:15</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">09:15 - 10:00</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                    </tr>
                    <tr class="table-warning">
                        <td class="fw-bold">10:00 - 10:15</td>
                        <td colspan="5" class="text-center">ISTIRAHAT</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">10:15 - 11:00</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">11:00 - 11:45</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="text-center mt-3">
            <p class="text-muted">Jadwal akan dimuat setelah setup database selesai</p>
        </div>
    </div>
</div>
@endsection
