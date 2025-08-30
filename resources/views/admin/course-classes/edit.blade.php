@extends('adminlte::page')

@section('title', 'Edit Kelas Perkuliahan')

@section('content_header')
    <h1 class="mb-1">Edit Kelas Perkuliahan</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.academic-years.index') }}" wire:navigate>Tahun Ajaran</a> >
        <a href="{{ route('admin.academic-years.index', $academicYear->id) }}" wire:navigate>{{ $academicYear->name }}</a> >
        {{ $courseClass->course->name }} - Kelas {{ $courseClass->name }}
    </h5>
@stop

@section('content')
   <livewire:admin.course-class.course-class-form :academicYear="$academicYear" :program="$program" :courseClass="$courseClass" />
@stop