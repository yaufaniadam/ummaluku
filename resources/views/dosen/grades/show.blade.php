@extends('adminlte::page')
@section('title', 'Input Nilai')
@section('content_header')
    <h1 class="mb-1">Input Nilai</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('dosen.grades.input.index') }}">Daftar Kelas</a> > 
        {{ $courseClass->course->name }} - Kelas {{ $courseClass->name }}
    </h5>
@stop
@section('content')
    <livewire:dosen.grade.grade-input-form :courseClass="$courseClass" />
@stop