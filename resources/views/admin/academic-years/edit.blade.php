@extends('adminlte::page')
@section('title', 'Edit Tahun Ajaran')
@section('content_header')
    <h1>Edit Tahun Ajaran</h1>
@stop
@section('content')
    <livewire:admin.academic-year.academic-year-form :academicYear="$academicYear" />
@stop