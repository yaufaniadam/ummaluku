@extends('adminlte::page')

@section('title', 'Tambah Dosen Baru')

@section('content_header')
    <h1>Tambah Dosen Baru</h1>
@stop

@section('content')
    {{-- Memanggil komponen Livewire kita --}}
    <livewire:admin.lecturer.lecturer-form />
@stop