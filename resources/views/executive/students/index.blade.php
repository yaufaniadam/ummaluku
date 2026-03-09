@extends('adminlte::page')

@section('title', 'Daftar Mahasiswa')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar Mahasiswa</h1>
        <a href="{{ route('executive.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users mr-2"></i>Data Mahasiswa</h3>
        </div>
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
@stop

@section('js')
    {!! $dataTable->scripts() !!}
@stop
