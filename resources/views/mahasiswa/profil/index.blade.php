@extends('adminlte::page')
@section('title', 'Profil Saya')
@section('content_header')
    <h1>Profil & Biodata</h1>
@stop
@section('content')
    <livewire:mahasiswa.profil.update-form />
@stop