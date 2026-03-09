@extends('adminlte::page')

@section('title', 'Daftar Dosen')

@section('content_header')
    <h1>Daftar Dosen</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Dosen</h3>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
