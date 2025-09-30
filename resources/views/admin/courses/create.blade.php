@extends('adminlte::page')

@section('title', 'Tambah Mata Kuliah')

@section('content_header')
    <h1 class="mb-1">Tambah Mata Kuliah</h1>
    
@stop

@section('content')
    {{-- Memanggil komponen Livewire dan mengirimkan data kurikulum --}}
    <livewire:admin.course.course-form />
@stop