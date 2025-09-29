@extends('adminlte::page')
@section('title', 'Dashboard SDM')
@section('content_header')
    <h1>Dashboard SDM & Kepegawaian</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            {{-- Kita gunakan lagi komponen StatBox yang sudah ada --}}
            <x-stat-box title="Dosen Aktif" value="{{ $jumlahDosenAktif }}" icon="fas fa-user-tie" color="success" url="{{ route('admin.sdm.lecturers.index') }}"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-stat-box title="Tenaga Kependidikan" value="{{ $jumlahTendikAktif }}" icon="fas fa-user-cog" color="info" url="#"/>
        </div>
    </div>

    {{-- Di sini nanti kita bisa tambahkan konten lain seperti --}}
    {{-- Tabel pegawai yang akan pensiun, grafik sebaran pendidikan, dll. --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Sebaran Jabatan Fungsional Dosen</h3>
                </div>
                <div class="card-body">
                    {{-- Canvas untuk Chart.js --}}
                </div>
            </div>
        </div>
    </div>
@stop