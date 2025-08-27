@extends('adminlte::page')
@section('title', 'Manajemen Tahun Ajaran')
@section('content_header')
    <h1>Manajemen Tahun Ajaran</h1>
@stop
@section('content')
    @if (session('success')) <div class="alert alert-success alert-dismissible fade show" role="alert"> {{ session('success') }} <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> @endif
    <div x-data @academic-year-updated.window="window.LaravelDataTables['academicyear-table'].ajax.reload(null, false);">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Daftar Tahun Ajaran</h3>
                <div class="card-tools"><a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary btn-sm" wire:navigate>Tambah Baru</a></div>
            </div>
            <div class="card-body">{{ $dataTable->table() }}</div>
        </div>
    </div>
@stop
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush    