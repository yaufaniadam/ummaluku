@extends('adminlte::page')

@section('title', 'Edit Mata Kuliah')

@section('content_header')
    <h1 class="mb-1">Edit Mata Kuliah</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.akademik.courses.index') }}" wire:navigate>Mata Kuliah</a> >
        <a href="{{ route('admin.akademik.courses.index', $curriculum->id) }}" wire:navigate>{{ $curriculum->name }}</a> >
        {{ $course->name }}
    </h5>
@stop

@section('content')
    {{-- Memanggil komponen Livewire dan mengirimkan data kurikulum serta mata kuliah --}}
    <livewire:admin.course.course-form :course="$course" />
@stop