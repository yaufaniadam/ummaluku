@extends('adminlte::page')

@section('title', 'Data Tenaga Kependidikan')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Data Tenaga Kependidikan (Tendik)</h1>
        <a href="{{ route('admin.sdm.staff.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Tendik
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{ $dataTable->table() }}
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    {{ $dataTable->scripts() }}
@stop
