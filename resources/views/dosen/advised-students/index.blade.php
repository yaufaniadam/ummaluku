@extends('adminlte::page')

@section('title', 'Mahasiswa Bimbingan')

@section('content_header')
    <h1>Daftar Mahasiswa Bimbingan Akademik</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Semua Mahasiswa Bimbingan Anda</h3>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush