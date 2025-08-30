@extends('adminlte::page')
@section('title', 'Edit Data Mahasiswa')
@section('content_header')
    <h1>Edit Data Mahasiswa</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.students.index') }}" wire:navigate>Manajemen Mahasiswa</a> > {{ $student->user->name }}
    </h5>
@stop
@section('content')
    <livewire:admin.student.student-form :student="$student" />
@stop