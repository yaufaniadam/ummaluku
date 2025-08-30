@extends('adminlte::page')

@section('title', 'Pengaturan Biaya Kuliah')

@section('content_header')
    <h1>Pengaturan Biaya Kuliah</h1>
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

    {{-- TAMBAHKAN BLOK INI UNTUK MENAMPILKAN PESAN ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div x-data @tuition-fee-updated.window="window.LaravelDataTables['tuitionfee-table'].ajax.reload(null, false);">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Biaya Kuliah per Prodi & Angkatan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.tuition-fees.create') }}" class="btn btn-primary btn-sm" wire:navigate>Tambah Biaya Baru</a>
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