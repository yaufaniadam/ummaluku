@extends('adminlte::page')

@section('title', 'Edit Kurikulum')

@section('content_header')
    <h1>Edit Kurikulum</h1>
@stop

@section('content')
    <livewire:admin.curriculum.curriculum-form :curriculum="$curriculum" />
@stop