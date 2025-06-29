@extends('layouts.frontend')

@section('title', 'Dashboard Pendaftar')

@section('content')

    {{-- View "Wadah" ini hanya bertugas memanggil komponen Livewire --}}
    @livewire('pendaftar.dashboard')
@endsection