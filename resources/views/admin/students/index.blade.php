@extends('adminlte::page')

@section('title', 'Manajemen Mahasiswa')

@section('content_header')
    <h1>Manajemen Mahasiswa</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div x-data @student-updated.window="window.LaravelDataTables['student-table'].ajax.reload(null, false);">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Mahasiswa</h3>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
