@extends('adminlte::page')

@section('title', 'Tambah Kelas Baru')

@section('content_header')
    <h1 class="mb-1">Tambah Kelas Baru</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.academic-years.index') }}" wire:navigate>Tahun Ajaran</a> >
        <a href="{{ route('admin.academic-years.index', $academicYear->id) }}" wire:navigate>{{ $academicYear->name }}</a> >
        Tambah Kelas
    </h5>
@stop

@section('content')
    <livewire:admin.course-class.course-class-form :academicYear="$academicYear" :program="$program" />
@stop