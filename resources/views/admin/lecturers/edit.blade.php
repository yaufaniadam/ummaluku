@extends('adminlte::page')

@section('title', 'Edit Dosen')

@section('content_header')
    <h1>Edit Dosen</h1>
@stop

@section('content')
    {{-- Memanggil komponen Livewire dan mengirimkan data dosen yang akan diedit --}}
    <livewire:admin.lecturer.lecturer-form :lecturer="$lecturer" />
@stop