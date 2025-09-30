@extends('adminlte::page')

@section('title', 'Tambah Mata Kuliah ke Kurikulum')

@section('content_header')
    <h1 class="mb-1">Tambah Mata Kuliah</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.akademik.curriculums.index') }}" wire:navigate>Manajemen Kurikulum</a> >
        <a href="{{ route('admin.akademik.curriculums.courses.index', $curriculum->id) }}" wire:navigate>{{ $curriculum->name }}</a> >
        Tambah dari Master
    </h5>
@stop

@section('content')
    {{-- Panggil komponen Livewire dan kirim data kurikulum --}}
    <livewire:admin.curriculum.course-selection :curriculum="$curriculum" />
@stop