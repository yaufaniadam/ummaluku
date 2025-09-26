@extends('adminlte::page')
@section('title', 'Edit Event Akademik')
@section('content_header')
    <h1>Edit Event Akademik</h1>
@stop
@section('content')
    <livewire:admin.academic-event.academic-event-form :academicEvent="$academicEvent" />
@stop