@extends('adminlte::page')

@section('title', 'Daftar Tenaga Kependidikan')

@section('content_header')
    <h1>Daftar Tenaga Kependidikan (Tendik)</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Tendik</h3>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@stop

@section('js')
    {{ $dataTable->scripts() }}
@stop
