@extends('adminlte::page')

@section('title', 'Data Mahasiswa Diterima')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Data Mahasiswa Diterima (Admisi)</h1>
        <a href="{{ route('executive.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-check mr-2"></i>Daftar Mahasiswa Diterima</h3>
        </div>
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
@stop

@section('js')
    {!! $dataTable->scripts() !!}
@stop
