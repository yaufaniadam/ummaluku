@extends('adminlte::page')
@section('title', 'Pengisian KRS')
@section('content_header')
    <h1>Pengisian Kartu Rencana Studi (KRS)</h1>
@stop
@section('content')
    @if (session('success-page'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success-page') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <livewire:mahasiswa.krs.pengisian-krs />
@stop