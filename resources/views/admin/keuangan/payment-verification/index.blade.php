@extends('adminlte::page')
@section('title', 'Verifikasi Pembayaran')
@section('content_header')
    <h1>Verifikasi Pembayaran Semester</h1>
@stop
@section('content')
    <div class="card">
        <div class="card-header"><h3 class="card-title">Daftar Tagihan Mahasiswa</h3></div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@stop
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush