@extends('adminlte::page')

@section('title', 'Manajemen Kurikulum')

@section('content_header')
    <h1>Manajemen Kurikulum</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kurikulum</h3>
            <div class="card-tools">
                <a href="{{ route('admin.akademik.curriculums.create') }}" class="btn btn-primary btn-sm" wire:navigate>Tambah Kurikulum Baru</a>
            </div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush