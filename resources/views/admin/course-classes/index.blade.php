@extends('adminlte::page')

@section('title', 'Kelola Kelas Perkuliahan')

@section('content_header')
    <h1 class="mb-1">Kelola Kelas Perkuliahan</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.academic-years.index') }}" wire:navigate>Tahun Ajaran</a> > {{ $academicYear->name }}
    </h5>
@stop

@section('content')

    <livewire:admin.course-class.actions />

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kelas</h3>
            <div class="card-tools">
                <a href="{{ route('admin.academic-years.course-classes.create', ['academic_year' => $academicYear]) }}"
                    class="btn btn-primary btn-sm" wire:navigate>
                    Tambah Kelas Baru
                </a>
            </div>
        </div>
        <div x-data @course-class-updated.window="window.LaravelDataTables['courseclass-table'].ajax.reload(null, false);">
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@stop

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
