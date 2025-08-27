@extends('adminlte::page')

@section('title', 'Tambah Mata Kuliah')

@section('content_header')
    <h1 class="mb-1">Tambah Mata Kuliah</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.curriculums.index') }}" wire:navigate>Kurikulum</a> > 
        <a href="{{ route('admin.curriculums.courses.index', $curriculum->id) }}" wire:navigate>{{ $curriculum->name }}</a> > 
        Tambah Baru
    </h5>
@stop

@section('content')
    {{-- Memanggil komponen Livewire dan mengirimkan data kurikulum --}}
    <livewire:admin.course.course-form :curriculum="$curriculum" />
@stop