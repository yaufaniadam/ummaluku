@extends('adminlte::page')
@section('title', 'Dashboard Keuangan')
@section('content_header')
    <h1>Dashboard Keuangan</h1>
    @if($activeSemester)
        <h5 class="font-weight-light">Data untuk Semester: {{ $activeSemester->name }}</h5>
    @else
        <h5 class="font-weight-light text-danger">Tidak ada semester akademik yang aktif.</h5>
    @endif
@stop
@section('content')
    <div class="row">
        <div class="col-lg-4 col-6">
            <x-stat-box title="Pendapatan Semester Ini" value="Rp {{ number_format($pendapatanSemesterIni, 0, ',', '.') }}" icon="fas fa-hand-holding-usd" color="success" url="#"/>
        </div>
        <div class="col-lg-4 col-6">
            <x-stat-box title="Tagihan Belum Lunas" value="{{ $jumlahTagihanBelumLunas }} Tagihan" icon="fas fa-file-invoice-dollar" color="warning" url="#"/>
        </div>
        <div class="col-lg-4 col-6">
            <x-stat-box title="Nominal Tunggakan" value="Rp {{ number_format($nominalTagihanBelumLunas, 0, ',', '.') }}" icon="fas fa-exclamation-triangle" color="danger" url="#"/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pendapatan per Bulan</h3>
                </div>
                <div class="card-body">
                    {{-- Canvas untuk Chart.js --}}
                </div>
            </div>
        </div>
    </div>
@stop