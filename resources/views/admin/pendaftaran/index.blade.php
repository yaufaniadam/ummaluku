@extends('adminlte::page')

@section('title', 'Data Pendaftar')

@section('content_header')
    <h1>Data Pendaftar</h1>
@stop


@section('content')
    {{-- Ini adalah view "wadah" yang akan menyuntikkan komponen Livewire tabel data pendaftar --}}
    @livewire('admin.pendaftaran.index')
@endsection