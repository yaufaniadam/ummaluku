@extends('adminlte::page')

@section('title', 'Data Mahasiswa')

@section('content_header')
    <h1>Data Induk Mahasiswa</h1>
@stop

@section('content')
    <p>Halaman ini berisi daftar semua mahasiswa yang telah terdaftar secara resmi di sistem.</p>
    
    <div class="card">
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
@stop

@push('js')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
@endpush