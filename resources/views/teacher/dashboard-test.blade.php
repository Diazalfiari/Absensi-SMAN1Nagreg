@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Dashboard Guru - Testing</h1>
            <p>Selamat datang, {{ auth()->user()->name }}!</p>
            
            @if(isset($teacher))
                <div class="alert alert-success">
                    Data Teacher ditemukan: {{ $teacher->name }} (ID: {{ $teacher->id }})
                </div>
            @else
                <div class="alert alert-danger">
                    Data Teacher tidak ditemukan!
                </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    <h5>Statistik Hari Ini:</h5>
                    <ul>
                        <li>Jadwal Hari Ini: {{ $todaySchedules->count() ?? 0 }}</li>
                        <li>Total Jadwal: {{ $totalSchedules ?? 0 }}</li>
                        <li>Total Siswa: {{ $totalStudents ?? 0 }}</li>
                    </ul>
                    
                    <h5>Absensi Hari Ini:</h5>
                    <ul>
                        <li>Hadir: {{ $attendanceStats['hadir'] ?? 0 }}</li>
                        <li>Sakit: {{ $attendanceStats['sakit'] ?? 0 }}</li>
                        <li>Izin: {{ $attendanceStats['izin'] ?? 0 }}</li>
                        <li>Alpha: {{ $attendanceStats['alpha'] ?? 0 }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
