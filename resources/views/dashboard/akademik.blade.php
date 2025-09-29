@extends('adminlte::page')
@section('title', 'Dashboard Akademik')
@section('content_header')
    <h1>Dashboard Akademik</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <x-stat-box title="Mahasiswa Aktif" value="{{ $jumlahMahasiswaAktif }}" icon="fas fa-user-graduate" color="info" url="#"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-stat-box title="Dosen Aktif" value="{{ $jumlahDosenAktif }}" icon="fas fa-user-tie" color="success" url="#"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-stat-box title="Kelas Dibuka Semester Ini" value="{{ $jumlahKelasDibuka }}" icon="fas fa-chalkboard" color="warning" url="#"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-stat-box title="KRS Perlu Persetujuan" value="{{ $jumlahKrsPending }}" icon="fas fa-user-clock" color="danger" url="#"/>
        </div>
    </div>

    {{-- Di sini nanti kita bisa tambahkan grafik (Chart.js) --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Mahasiswa per Angkatan</h3>
                </div>
                <div class="card-body">
                    {{-- Canvas untuk Chart.js --}}
                </div>
            </div>
        </div>
    </div>
@stop