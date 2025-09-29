@extends('adminlte::page')

@section('title', 'Master Komponen Biaya')

@section('content_header')
    <h1>Master Komponen Biaya</h1>
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
    <div x-data @fee-component-updated.window="window.LaravelDataTables['feecomponent-table'].ajax.reload(null, false);">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Semua Jenis Biaya</h3>
               <div class="card-tools">
                    <a href="{{ route('admin.keuangan.fee-components.create') }}" class="btn btn-primary btn-sm" wire:navigate>Tambah Komponen Baru</a>
                </div>
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
