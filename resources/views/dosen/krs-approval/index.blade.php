@extends('adminlte::page')

@section('title', 'Persetujuan KRS')

@section('content_header')
    <h1>Persetujuan KRS Mahasiswa Bimbingan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Mahasiswa Menunggu Persetujuan</h3>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush