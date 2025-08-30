@extends('adminlte::page')
@section('title', 'Proses Persetujuan KRS')
@section('content_header')
    <h1>Proses Persetujuan KRS</h1>
@stop
@section('content')
    <livewire:dosen.krs.approval-detail :student="$student" />
@stop