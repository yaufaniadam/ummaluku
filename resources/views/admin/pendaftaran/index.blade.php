@extends('tablar::page')

@section('content')
    {{-- Ini adalah view "wadah" yang akan menyuntikkan komponen Livewire tabel data pendaftar --}}
    @livewire('admin.pendaftaran.index')
@endsection